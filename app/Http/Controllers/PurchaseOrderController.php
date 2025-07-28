<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
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
        $poNumber = $this->generatePoNumber();
        return view('purchase-orders.create', compact('vendors', 'poNumber'));
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
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'shipping' => 'required|numeric',
            'other' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|string|in:draft,sent,approved,completed,cancelled',
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
                    'sub_total' => $validatedData['sub_total'],
                    'tax' => $validatedData['tax'],
                    'shipping' => $validatedData['shipping'],
                    'other' => $validatedData['other'],
                    'grand_total' => $validatedData['grand_total'],
                    'notes' => $validatedData['notes'],
                    'terms_and_conditions' => $validatedData['terms_and_conditions'],
                    'status' => $validatedData['status'] ?? 'draft',
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

        $purchaseOrder->load('vendor');
        $vendors = Vendor::orderBy('company_name')->get();
        return view('purchase-orders.edit', compact('purchaseOrder', 'vendors'));
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
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'shipping' => 'required|numeric',
            'other' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|string|in:draft,sent,approved,completed,cancelled',
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
                'sub_total' => $validatedData['sub_total'],
                'tax' => $validatedData['tax'],
                'shipping' => $validatedData['shipping'],
                'other' => $validatedData['other'],
                'grand_total' => $validatedData['grand_total'],
                'notes' => $validatedData['notes'],
                'terms_and_conditions' => $validatedData['terms_and_conditions'],
                'status' => $validatedData['status'],
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

    /**
     * Generate the next PO number in AY-YYYY-YY-XXXX format.
     */
    protected function generatePoNumber()
    {
        $now = now();
        $year = $now->month >= 4 ? $now->year : $now->year - 1;
        $nextYear = $year + 1;
        $ay = sprintf('AY-%d-%02d', $year, $nextYear % 100);
        $start = now()->setDate($year, 4, 1)->startOfDay();
        $end = now()->setDate($nextYear, 3, 31)->endOfDay();
        $lastPo = \App\Models\PurchaseOrder::whereBetween('po_date', [$start, $end])
            ->where('po_number', 'like', $ay . '-%')
            ->orderByDesc('id')->first();
        $nextSeq = 1;
        if ($lastPo && preg_match('/-(\\d{4})$/', $lastPo->po_number, $m)) {
            $nextSeq = intval($m[1]) + 1;
        }
        return $ay . '-' . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
