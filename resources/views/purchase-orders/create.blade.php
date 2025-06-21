@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Create New Purchase Order</h1>

    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('purchase-orders.store') }}" method="POST" id="po-form" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <input type="hidden" name="vendor_id" id="vendor_id_input" required>
        <input type="hidden" name="ship_to_address_id" id="ship_to_address_id_input" required>
        <input type="hidden" name="po_number" id="po_number_input">

        <!-- PO Details -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="po_number_display" class="block text-sm font-medium text-gray-700">PO Number</label>
                <input type="text" id="po_number_display" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" disabled>
            </div>
            <div>
                <label for="po_date" class="block text-sm font-medium text-gray-700">PO Date</label>
                <input type="date" name="po_date" id="po_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required value="{{ old('po_date', date('Y-m-d')) }}">
            </div>
            <div>
                <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700">Expected Delivery Date</label>
                <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('expected_delivery_date') }}">
            </div>
             <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="draft" selected>Draft</option>
                    <option value="sent">Sent</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                <select name="payment_status" id="payment_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="unpaid" selected>Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="partially_paid">Partially Paid</option>
                </select>
            </div>
        </div>

        <!-- Vendor and Ship To Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Vendor</h3>
                    <button type="button" id="select-vendor-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Select Vendor</button>
                </div>
                <div id="vendor-details" class="text-sm text-gray-600 space-y-1">
                    <p>No vendor selected.</p>
                </div>
            </div>
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Ship To Address</h3>
                    <button type="button" id="select-address-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Select Address</button>
                </div>
                <div id="address-details" class="text-sm text-gray-600 space-y-1">
                    <p>No address selected.</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Items</h2>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4">Item Name</th>
                        <th class="py-2 px-4">Description</th>
                        <th class="py-2 px-4">Qty</th>
                        <th class="py-2 px-4">Unit Price</th>
                        <th class="py-2 px-4">GST (%)</th>
                        <th class="py-2 px-4">GST Amount</th>
                        <th class="py-2 px-4">Total</th>
                        <th class="py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody id="items-tbody">
                    <!-- JS generated -->
                </tbody>
            </table>
            <button type="button" id="add-item-btn" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Item</button>
        </div>

        <!-- Summary and Notes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                </div>
                <div>
                    <label for="terms_and_conditions" class="block text-sm font-medium text-gray-700">Terms & Conditions</label>
                    <textarea name="terms_and_conditions" id="terms_and_conditions" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('terms_and_conditions') }}</textarea>
                </div>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-4">Summary</h3>
                <div class="flex justify-between mb-2"><span>Sub Total</span><span id="sub-total">₹0.00</span></div>
                <div class="flex justify-between mb-2"><span>Total GST</span><span id="total-gst">₹0.00</span></div>
                <div class="flex justify-between font-bold text-lg"><span>Grand Total</span><span id="grand-total">₹0.00</span></div>
            </div>
        </div>

        <input type="hidden" name="sub_total" id="sub_total_input">
        <input type="hidden" name="total_gst" id="total_gst_input">
        <input type="hidden" name="grand_total" id="grand_total_input">

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('purchase-orders.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Save</button>
        </div>
    </form>
</div>

<!-- Vendor Modal -->
<div id="vendor-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-full overflow-y-auto">
        <div class="p-4 border-b">
            <h3 class="text-lg font-bold">Select a Vendor</h3>
        </div>
        <div class="p-4">
            <input type="text" id="vendor-search" placeholder="Search vendors..." class="w-full p-2 border rounded-md mb-4">
            <div id="vendor-list" class="space-y-2">
                <!-- JS generated -->
            </div>
        </div>
        <div class="p-4 border-t flex justify-end">
             <button type="button" id="close-vendor-modal" class="bg-gray-500 text-white px-4 py-2 rounded-md">Close</button>
        </div>
    </div>
</div>

<!-- Address Modal -->
<div id="address-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-full overflow-y-auto">
        <div class="p-4 border-b">
            <h3 class="text-lg font-bold">Select a Shipping Address</h3>
        </div>
        <div class="p-4">
            <input type="text" id="address-search" placeholder="Search addresses..." class="w-full p-2 border rounded-md mb-4">
            <div id="address-list" class="space-y-2">
                <!-- JS generated -->
            </div>
        </div>
        <div class="p-4 border-t flex justify-end">
             <button type="button" id="close-address-modal" class="bg-gray-500 text-white px-4 py-2 rounded-md">Close</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const vendors = @json($vendors);
    const shipToAddresses = @json($shipToAddresses);

    // PO Number Generation
    const poNumberInput = document.getElementById('po_number_input');
    const poNumberDisplay = document.getElementById('po_number_display');
    if (!poNumberInput.value) {
        const date = new Date();
        const poNumber = `PO-${date.getFullYear()}${('0' + (date.getMonth() + 1)).slice(-2)}${('0' + date.getDate()).slice(-2)}-${Math.random().toString(36).substring(2, 8).toUpperCase()}`;
        poNumberInput.value = poNumber;
        poNumberDisplay.value = poNumber;
    }

    // Modal Handling
    const vendorModal = document.getElementById('vendor-modal');
    const addressModal = document.getElementById('address-modal');
    const selectVendorBtn = document.getElementById('select-vendor-btn');
    const selectAddressBtn = document.getElementById('select-address-btn');

    selectVendorBtn.addEventListener('click', () => vendorModal.classList.remove('hidden'));
    selectAddressBtn.addEventListener('click', () => addressModal.classList.remove('hidden'));
    document.getElementById('close-vendor-modal').addEventListener('click', () => vendorModal.classList.add('hidden'));
    document.getElementById('close-address-modal').addEventListener('click', () => addressModal.classList.add('hidden'));

    // Vendor Selection
    const vendorList = document.getElementById('vendor-list');
    const vendorDetails = document.getElementById('vendor-details');
    const vendorIdInput = document.getElementById('vendor_id_input');
    const vendorSearch = document.getElementById('vendor-search');

    function renderVendors(filter = '') {
        vendorList.innerHTML = '';
        vendors.filter(v => v.company_name.toLowerCase().includes(filter.toLowerCase())).forEach(vendor => {
            const div = document.createElement('div');
            div.className = 'p-2 border rounded-md cursor-pointer hover:bg-gray-100';
            div.textContent = `${vendor.company_name} (${vendor.email})`;
            div.dataset.id = vendor.id;
            div.addEventListener('click', () => {
                vendorIdInput.value = vendor.id;
                vendorDetails.innerHTML = `
                    <p><strong>${vendor.company_name}</strong></p>
                    <p>${vendor.address}, ${vendor.city}, ${vendor.state} - ${vendor.zipcode}</p>
                    <p>${vendor.email} | ${vendor.phone}</p>
                `;
                vendorModal.classList.add('hidden');
            });
            vendorList.appendChild(div);
        });
    }
    vendorSearch.addEventListener('input', (e) => renderVendors(e.target.value));
    renderVendors();

    // Address Selection
    const addressList = document.getElementById('address-list');
    const addressDetails = document.getElementById('address-details');
    const addressIdInput = document.getElementById('ship_to_address_id_input');
    const addressSearch = document.getElementById('address-search');

    function renderAddresses(filter = '') {
        addressList.innerHTML = '';
        shipToAddresses.filter(a => a.name.toLowerCase().includes(filter.toLowerCase()) || a.address_line_1.toLowerCase().includes(filter.toLowerCase())).forEach(address => {
            const div = document.createElement('div');
            div.className = 'p-2 border rounded-md cursor-pointer hover:bg-gray-100';
            div.textContent = `${address.name} - ${address.address_line_1}, ${address.city}`;
            div.dataset.id = address.id;
            div.addEventListener('click', () => {
                addressIdInput.value = address.id;
                addressDetails.innerHTML = `
                    <p><strong>${address.name}</strong></p>
                    <p>${address.address_line_1}, ${address.city}, ${address.state} - ${address.zipcode}</p>
                    <p>${address.contact_person || ''} | ${address.phone || ''}</p>
                `;
                addressModal.classList.add('hidden');
            });
            addressList.appendChild(div);
        });
    }
    addressSearch.addEventListener('input', (e) => renderAddresses(e.target.value));
    renderAddresses();


    // Items Table Logic
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsTbody = document.getElementById('items-tbody');
    let itemIndex = 0;

    function addItemRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="border px-2 py-1"><input type="text" name="items[${itemIndex}][item_name]" class="w-full border-gray-300 rounded text-sm" required></td>
            <td class="border px-2 py-1"><input type="text" name="items[${itemIndex}][description]" class="w-full border-gray-300 rounded text-sm"></td>
            <td class="border px-2 py-1"><input type="number" name="items[${itemIndex}][qty]" class="w-20 border-gray-300 rounded item-qty text-sm" required min="1" value="1"></td>
            <td class="border px-2 py-1"><input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="w-24 border-gray-300 rounded item-price text-sm" required min="0" value="0"></td>
            <td class="border px-2 py-1"><input type="number" step="0.01" name="items[${itemIndex}][gst_percent]" class="w-20 border-gray-300 rounded item-gst text-sm" min="0" value="0"></td>
            <td class="border px-2 py-1 item-gst-amount text-sm">₹0.00</td>
            <td class="border px-2 py-1 item-total text-sm">₹0.00</td>
            <td class="border px-2 py-1 text-center">
                <button type="button" class="text-red-500 hover:text-red-700 remove-item-btn p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                </button>
            </td>
        `;
        itemsTbody.appendChild(tr);
        itemIndex++;
        updateTotals();
    }

    addItemBtn.addEventListener('click', addItemRow);

    itemsTbody.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item-btn')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    itemsTbody.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price') || e.target.classList.contains('item-gst')) {
            updateTotals();
        }
    });

    function updateTotals() {
        let subTotal = 0;
        let totalGst = 0;

        itemsTbody.querySelectorAll('tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const gstPercent = parseFloat(row.querySelector('.item-gst').value) || 0;

            const itemSubTotal = qty * price;
            const itemGst = itemSubTotal * (gstPercent / 100);
            const itemTotal = itemSubTotal + itemGst;

            row.querySelector('.item-gst-amount').textContent = '₹' + itemGst.toFixed(2);
            row.querySelector('.item-total').textContent = '₹' + itemTotal.toFixed(2);

            subTotal += itemSubTotal;
            totalGst += itemGst;
        });

        const grandTotal = subTotal + totalGst;

        document.getElementById('sub-total').textContent = '₹' + subTotal.toFixed(2);
        document.getElementById('total-gst').textContent = '₹' + totalGst.toFixed(2);
        document.getElementById('grand-total').textContent = '₹' + grandTotal.toFixed(2);

        document.getElementById('sub_total_input').value = subTotal.toFixed(2);
        document.getElementById('total_gst_input').value = totalGst.toFixed(2);
        document.getElementById('grand_total_input').value = grandTotal.toFixed(2);
    }

    addItemRow(); // Add initial row
});
</script>
@endsection
