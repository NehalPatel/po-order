@extends('layouts.admin')

@section('content')
<style>
    .po-container {
        font-family: 'Arial', sans-serif;
        color: #333;
    }
    .header-section, .footer-section {
        border-bottom: 2px solid #000;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .footer-section {
        border-top: 2px solid #000;
        border-bottom: none;
        padding-top: 1rem;
        margin-top: 1.5rem;
        margin-bottom: 0;
    }
    .total-amount {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .print-button {
        background-color: #4A5568;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .print-button:hover {
        background-color: #2D3748;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #po-details, #po-details * {
            visibility: visible;
        }
        #po-details {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none;
        }
    }
</style>

<div id="po-details" class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="p-8 po-container">
        <!-- Header -->
        <div class="header-section grid grid-cols-2 gap-4 items-start">
            <div>
                @php
                    $settings = optional($purchaseOrder->team)->settings;
                    $setting = $settings ? $settings->first() : null;
                @endphp
                <h1 class="text-4xl font-bold uppercase">{{ $setting ? $setting->company_name : 'Your Company' }}</h1>
                <p class="text-gray-600">{{ $setting ? $setting->street_address : '123 Main St' }}</p>
                <p class="text-gray-600">{{ $setting ? $setting->city : 'Anytown' }}, {{ $setting ? $setting->state : 'ST' }} {{ $setting ? $setting->zipcode : '12345' }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-bold uppercase text-gray-800">Purchase Order</h2>
                <p class="text-lg mt-2"><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
                <p><strong>Date:</strong> {{ $purchaseOrder->po_date->format('F j, Y') }}</p>
                <div class="mt-3 flex flex-col items-end space-y-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if($purchaseOrder->status == 'draft') bg-gray-200 text-gray-800
                        @elseif($purchaseOrder->status == 'sent') bg-blue-100 text-blue-800
                        @elseif($purchaseOrder->status == 'approved') bg-green-100 text-green-800
                        @elseif($purchaseOrder->status == 'completed') bg-purple-100 text-purple-800
                        @elseif($purchaseOrder->status == 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($purchaseOrder->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Vendor and Ship To -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="font-bold border-b pb-2 mb-2">VENDOR</h3>
                @if($purchaseOrder->vendor)
                    <p class="font-semibold">{{ $purchaseOrder->vendor->company_name }}</p>
                    <p>{{ $purchaseOrder->vendor->address }}<br>{{ $purchaseOrder->vendor->city }}, {{ $purchaseOrder->vendor->state }} {{ $purchaseOrder->vendor->zip_code }}</p>
                    <p><strong>Contact:</strong> {{ $purchaseOrder->vendor->contact_person }}</p>
                    <p><strong>Email:</strong> {{ $purchaseOrder->vendor->email }}</p>
                    <p><strong>Phone:</strong> {{ $purchaseOrder->vendor->phone }}</p>
                @else
                    <p class="text-red-500">Vendor not found.</p>
                @endif
            </div>
            <!-- Remove the Ship To section entirely -->
        </div>

        <!-- Items Table -->
        <table class="w-full text-left table-auto">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-4 uppercase">Item Description</th>
                    <th class="p-4 uppercase text-right">Qty</th>
                    <th class="p-4 uppercase text-right">Unit Price</th>
                    <th class="p-4 uppercase text-right">GST</th>
                    <th class="p-4 uppercase text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->items as $item)
                    <tr class="border-b">
                        <td class="p-4">{{ $item->item_name }}</td>
                        <td class="p-4 text-right">{{ $item->qty }}</td>
                        <td class="p-4 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="p-4 text-right">₹{{ number_format($item->gst, 2) }} ({{$item->gst_percentage}}%)</td>
                        <td class="p-4 text-right">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end mt-8">
            <div class="w-full md:w-1/3">
                <div class="flex justify-between py-2 border-b">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($purchaseOrder->sub_total, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span>Total GST</span>
                    <span>₹{{ number_format($purchaseOrder->tax, 2) }}</span>
                </div>
                @if($purchaseOrder->shipping > 0)
                <div class="flex justify-between py-2 border-b">
                    <span>Shipping</span>
                    <span>₹{{ number_format($purchaseOrder->shipping, 2) }}</span>
                </div>
                @endif
                @if($purchaseOrder->other > 0)
                <div class="flex justify-between py-2 border-b">
                    <span>Other Charges</span>
                    <span>₹{{ number_format($purchaseOrder->other, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2 font-bold text-lg">
                    <span>Grand Total</span>
                    <span>₹{{ number_format($purchaseOrder->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        <div class="mt-8 grid grid-cols-1 gap-8">
             @if($purchaseOrder->notes)
            <div>
                <h4 class="font-bold mb-2">Notes</h4>
                <p class="text-gray-600">{{ $purchaseOrder->notes }}</p>
            </div>
            @endif
             @if($purchaseOrder->terms_and_conditions)
            <div>
                <h4 class="font-bold mb-2">Terms & Conditions</h4>
                <p class="text-gray-600">{{ $purchaseOrder->terms_and_conditions }}</p>
            </div>
            @endif
        </div>


        <!-- Footer -->
        <div class="footer-section text-center">
            <p>Thank you for your business!</p>
            <p class="text-gray-500">{{ $setting ? $setting->company_name : 'Your Company' }} | {{ $setting ? $setting->website : 'yourwebsite.com' }}</p>
        </div>
    </div>
</div>
<div class="mt-6 flex justify-between items-center no-print">
    <a href="{{ route('purchase-orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to List
    </a>
    
    <div class="flex space-x-2">
        <button onclick="window.print()" class="print-button">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-14a2 2 0 100-4 2 2 0 000 4z" />
            </svg>
            Print Purchase Order
        </button>
        
        <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
        
        <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this purchase order?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
        </form>
    </div>
</div>
@endsection