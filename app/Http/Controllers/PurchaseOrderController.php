<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\ShipToAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['vendor', 'team'])
            ->latest()
            ->paginate(10);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::orderBy('company_name')->get();
        $shipToAddresses = ShipToAddress::where('team_id', Auth::user()->currentTeam->id)->get();

        return view('purchase-orders.create', compact('vendors', 'shipToAddresses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255|unique:purchase_orders',
            'po_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'comments' => 'nullable|string',
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'shipping' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percentage' => 'required|numeric|min:0',
            'items.*.gst' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::create([
                'team_id' => Auth::user()->currentTeam->id,
                'po_number' => $validatedData['po_number'],
                'po_date' => $validatedData['po_date'],
                'vendor_id' => $validatedData['vendor_id'],
                'comments' => $validatedData['comments'],
                'sub_total' => $validatedData['sub_total'],
                'tax' => $validatedData['tax'],
                'shipping' => $validatedData['shipping'],
                'other' => 0, // Assuming 'other' is not in the form for now
                'grand_total' => $validatedData['grand_total'],
                'status' => 'draft',
                'payment_status' => 'unpaid',
            ]);

            foreach ($validatedData['items'] as $itemData) {
                // Manually calculate GST for now.
                // This could be made more robust.
                $purchaseOrder->items()->create([
                    'item_name' => $itemData['item_name'],
                    'qty' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'gst_percentage' => $itemData['gst_percentage'],
                    'gst' => $itemData['gst'],
                    'total' => $itemData['total'],
                ]);
            }

            DB::commit();

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create purchase order. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['team.settings', 'team.shipToAddresses', 'vendor', 'items']);

        $setting = $purchaseOrder->team->settings->first();
        $shipTo = $purchaseOrder->team->shipToAddresses->first();

        return view('purchase-orders.show', compact('purchaseOrder', 'setting', 'shipTo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $vendors = Vendor::all();
        $shipToAddresses = ShipToAddress::where('team_id', Auth::user()->currentTeam->id)->get();
        $purchaseOrder->load('items', 'vendor', 'shipToAddress');
        return view('purchase-orders.edit', compact('purchaseOrder', 'vendors', 'shipToAddresses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'expected_date' => 'required|date',
            'ship_to_address_id' => 'required|exists:ship_to_addresses,id',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'sub_total' => 'required|numeric',
            'total_gst' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percentage' => 'required|numeric|min:0',
            'items.*.gst' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $purchaseOrder->update($validatedData);

        // Delete old items and add new ones
        $purchaseOrder->items()->delete();

        foreach ($validatedData['items'] as $itemData) {
            $purchaseOrder->items()->create($itemData);
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
