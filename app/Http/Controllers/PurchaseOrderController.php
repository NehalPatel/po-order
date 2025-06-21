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
        $purchaseOrders = PurchaseOrder::where('team_id', Auth::user()->currentTeam->id)
            ->with('vendor')
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
            'po_number' => 'required|string|unique:purchase_orders,po_number',
            'po_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'ship_to_address_id' => 'required|exists:ship_to_addresses,id',
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'shipping' => 'required|numeric',
            'other' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|string|in:draft,sent,approved,completed,cancelled',
            'payment_status' => 'required|string|in:unpaid,paid,partially_paid',
            'expected_delivery_date' => 'nullable|date|after_or_equal:po_date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percentage' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $purchaseOrder = PurchaseOrder::create([
                    'team_id' => Auth::user()->currentTeam->id,
                    'po_number' => $validatedData['po_number'],
                    'po_date' => $validatedData['po_date'],
                    'vendor_id' => $validatedData['vendor_id'],
                    'ship_to_address_id' => $validatedData['ship_to_address_id'],
                    'sub_total' => $validatedData['sub_total'],
                    'tax' => $validatedData['tax'],
                    'shipping' => $validatedData['shipping'],
                    'other' => $validatedData['other'],
                    'grand_total' => $validatedData['grand_total'],
                    'notes' => $validatedData['notes'],
                    'terms_and_conditions' => $validatedData['terms_and_conditions'],
                    'status' => $validatedData['status'] ?? 'draft',
                    'payment_status' => $validatedData['payment_status'] ?? 'unpaid',
                    'expected_delivery_date' => $validatedData['expected_delivery_date'],
                ]);

                foreach ($validatedData['items'] as $itemData) {
                    $itemSubTotal = $itemData['qty'] * $itemData['unit_price'];
                    $gstAmount = $itemSubTotal * ($itemData['gst_percentage'] / 100);
                    $total = $itemSubTotal + $gstAmount;

                    $purchaseOrder->items()->create([
                        'item_name' => $itemData['item_name'],
                        'qty' => $itemData['qty'],
                        'unit_price' => $itemData['unit_price'],
                        'gst_percentage' => $itemData['gst_percentage'],
                        'gst' => $gstAmount,
                        'total' => $total,
                    ]);
                }
            });

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create purchase order. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->team_id !== Auth::user()->currentTeam->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->team_id !== Auth::user()->currentTeam->id) {
            abort(403, 'Unauthorized action.');
        }

        $purchaseOrder->load('vendor', 'shipToAddress');
        $vendors = Vendor::orderBy('company_name')->get();
        $shipToAddresses = ShipToAddress::where('team_id', Auth::user()->currentTeam->id)->get();
        return view('purchase-orders.edit', compact('purchaseOrder', 'vendors', 'shipToAddresses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->team_id !== Auth::user()->currentTeam->id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255|unique:purchase_orders,po_number,' . $purchaseOrder->id,
            'po_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'ship_to_address_id' => 'required|exists:ship_to_addresses,id',
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'shipping' => 'required|numeric',
            'other' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|string|in:draft,sent,approved,completed,cancelled',
            'payment_status' => 'required|string|in:unpaid,paid,partially_paid',
            'expected_delivery_date' => 'required|date|after_or_equal:po_date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer|exists:purchase_order_items,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percentage' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($purchaseOrder, $validatedData) {
            $purchaseOrder->update([
                'po_number' => $validatedData['po_number'],
                'po_date' => $validatedData['po_date'],
                'vendor_id' => $validatedData['vendor_id'],
                'ship_to_address_id' => $validatedData['ship_to_address_id'],
                'sub_total' => $validatedData['sub_total'],
                'tax' => $validatedData['tax'],
                'shipping' => $validatedData['shipping'],
                'other' => $validatedData['other'],
                'grand_total' => $validatedData['grand_total'],
                'notes' => $validatedData['notes'],
                'terms_and_conditions' => $validatedData['terms_and_conditions'],
                'status' => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'expected_delivery_date' => $validatedData['expected_delivery_date'],
            ]);

            $existingIds = $purchaseOrder->items->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($validatedData['items'] as $itemData) {
                $itemSubTotal = $itemData['qty'] * $itemData['unit_price'];
                $gstAmount = $itemSubTotal * ($itemData['gst_percentage'] / 100);
                $total = $itemSubTotal + $gstAmount;

                $itemPayload = [
                    'item_name' => $itemData['item_name'],
                    'qty' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'gst_percentage' => $itemData['gst_percentage'],
                    'gst' => $gstAmount,
                    'total' => $total,
                ];

                if (!empty($itemData['id'])) {
                    $item = $purchaseOrder->items()->find($itemData['id']);
                    if ($item) {
                        $item->update($itemPayload);
                        $updatedIds[] = $item->id;
                    }
                } else {
                    $newItem = $purchaseOrder->items()->create($itemPayload);
                    $updatedIds[] = $newItem->id;
                }
            }

            $idsToDelete = array_diff($existingIds, $updatedIds);
            if (!empty($idsToDelete)) {
                $purchaseOrder->items()->whereIn('id', $idsToDelete)->delete();
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->team_id !== Auth::user()->currentTeam->id) {
            abort(403, 'Unauthorized action.');
        }

        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted successfully.');
    }
}
