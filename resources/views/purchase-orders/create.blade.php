@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Purchase Order</h1>
        <p class="text-gray-600 mt-1">Fill out the form below to generate a new PO.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchase-orders.store') }}" method="POST">
        @csrf
        <div class="space-y-8">
            <!-- Main Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Main Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="vendor_id" class="block text-sm font-medium text-gray-700">Vendor</label>
                        <select id="vendor_id" name="vendor_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select a vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="po_date" class="block text-sm font-medium text-gray-700">Order Date</label>
                        <input type="date" id="po_date" name="po_date" value="{{ old('po_date', now()->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="ship_to_address_id" class="block text-sm font-medium text-gray-700">Ship To Address</label>
                         <select id="ship_to_address_id" name="ship_to_address_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select a shipping address</option>
                            @foreach($shipToAddresses as $address)
                                <option value="{{ $address->id }}" {{ old('ship_to_address_id') == $address->id ? 'selected' : '' }}>{{ $address->name }} - {{ $address->address }}, {{ $address->city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700">Expected Delivery Date</label>
                        <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Items</h3>
                    <button type="button" id="add-item-btn" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm">Add Item</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-5/12">Item Name</th>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Qty</th>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Unit Price</th>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">GST (%)</th>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Total</th>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12"></th>
                            </tr>
                        </thead>
                        <tbody id="items-container" class="bg-white divide-y divide-gray-200">
                           <!-- JS will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals & Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Notes & Terms</h3>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                    </div>
                    <div class="mt-4">
                        <label for="terms_conditions" class="block text-sm font-medium text-gray-700">Terms & Conditions</label>
                        <textarea id="terms_conditions" name="terms_conditions" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('terms_conditions') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 flex flex-col justify-between">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Summary</h3>
                    <div class="space-y-4">
                         <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal" class="font-medium">₹0.00</span>
                            <input type="hidden" name="sub_total" id="sub_total_input">
                        </div>
                         <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total GST:</span>
                            <span id="total-gst" class="font-medium">₹0.00</span>
                            <input type="hidden" name="total_gst" id="total_gst_input">
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Grand Total:</span>
                            <span id="grand-total">₹0.00</span>
                            <input type="hidden" name="grand_total" id="grand_total_input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('purchase-orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Create PO</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item-btn');
    let itemIndex = 0;

    function createItemRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-2 py-2"><input type="text" name="items[${itemIndex}][item_name]" class="form-input w-full border-gray-300 rounded-md shadow-sm"></td>
            <td class="px-2 py-2"><input type="number" name="items[${itemIndex}][qty]" class="item-qty form-input w-full border-gray-300 rounded-md shadow-sm" value="1" min="1"></td>
            <td class="px-2 py-2"><input type="number" name="items[${itemIndex}][unit_price]" class="item-price form-input w-full border-gray-300 rounded-md shadow-sm" placeholder="0.00" min="0" step="0.01"></td>
            <td class="px-2 py-2"><input type="number" name="items[${itemIndex}][gst_percentage]" class="item-gst-percent form-input w-full border-gray-300 rounded-md shadow-sm" placeholder="0" min="0" value="0"></td>
            <td class="px-2 py-2"><input type="text" class="item-total form-input w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly></td>
            <td class="px-2 py-2 text-center"><button type="button" class="remove-item-btn text-red-500 hover:text-red-700">&times;</button></td>
            <input type="hidden" name="items[${itemIndex}][gst]" class="item-gst-amount">
            <input type="hidden" name="items[${itemIndex}][total]" class="item-total-amount">
        `;
        itemsContainer.appendChild(row);
        itemIndex++;
    }

    addItemBtn.addEventListener('click', createItemRow);

    itemsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    itemsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price') || e.target.classList.contains('item-gst-percent')) {
            updateTotals();
        }
    });

    function updateTotals() {
        let subtotal = 0;
        let totalGst = 0;

        itemsContainer.querySelectorAll('tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const gstPercent = parseFloat(row.querySelector('.item-gst-percent').value) || 0;

            const baseTotal = qty * price;
            const gstAmount = baseTotal * (gstPercent / 100);
            const total = baseTotal + gstAmount;

            row.querySelector('.item-total').value = `₹${total.toFixed(2)}`;
            row.querySelector('.item-gst-amount').value = gstAmount.toFixed(2);
            row.querySelector('.item-total-amount').value = total.toFixed(2);

            subtotal += baseTotal;
            totalGst += gstAmount;
        });

        const grandTotal = subtotal + totalGst;

        document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
        document.getElementById('sub_total_input').value = subtotal.toFixed(2);

        document.getElementById('total-gst').textContent = `₹${totalGst.toFixed(2)}`;
        document.getElementById('total_gst_input').value = totalGst.toFixed(2);

        document.getElementById('grand-total').textContent = `₹${grandTotal.toFixed(2)}`;
        document.getElementById('grand_total_input').value = grandTotal.toFixed(2);
    }

    // Add initial row
    createItemRow();
});
</script>
@endsection
