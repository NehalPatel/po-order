@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/po-order.css') }}">

<div class="po-container">
    <div class="po-document">
        <!-- Header Section -->
        <div class="header">
            <div class="left-section">
                @php
                    $settings = optional($purchaseOrder->team)->settings;
                    $setting = $settings ? $settings->first() : null;
                @endphp
                
                @if($setting && $setting->logo)
                    <div class="logo">
                        <img src="{{ $setting->logo_url }}" alt="Company Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                    </div>
                @else
                    <div class="logo">
                        विया विनयन<br>शोभते
                    </div>
                @endif
                
                <div class="college-info">
                    <div class="college-name">
                        {{ $setting ? strtoupper($setting->company_name) : 'SDJ INTERNATIONAL COLLEGE' }}
                    </div>
                    <div class="managed-by">Managed by : Paras Education Trust</div>
                    <div class="address">
                        {!! $setting ? nl2br(e($setting->address)) : '' !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Reference Fields -->
        <div class="content">
            <div class="po-details">
                <div class="left-fields">
                    <div class="field-group-inline">
                        <label>Ref. P.P. No.</label>
                        <div class="field-value placeholder">{{ $purchaseOrder->po_number }}</div>
                    </div>
                    <div class="field-group">
                        <label>To,</label>
                        @if($purchaseOrder->vendor)
                            <div class="field-value">{{ $purchaseOrder->vendor->company_name }}</div>
                            <div class="vendor-address">
                                {{ $purchaseOrder->vendor->address }}<br>
                                @if($purchaseOrder->vendor->city && $purchaseOrder->vendor->state && $purchaseOrder->vendor->zipcode)
                                    {{ $purchaseOrder->vendor->city }}, {{ $purchaseOrder->vendor->state }} {{ $purchaseOrder->vendor->zipcode }}<br>
                                @endif
                                @if($purchaseOrder->vendor->phone)
                                    Phone: {{ $purchaseOrder->vendor->phone }}
                                @endif
                                @if($purchaseOrder->vendor->email)
                                    @if($purchaseOrder->vendor->phone) | @endif Email: {{ $purchaseOrder->vendor->email }}
                                @endif
                            </div>
                        @else
                            <div class="field-value">Vendor not found</div>
                        @endif
                    </div>
                </div>
                <div class="right-field">
                    <div class="field-group-inline">
                        <label>Dated :</label>
                        <div class="field-value placeholder">{{ $purchaseOrder->po_date->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Order Title -->
        <div class="po-title">Purchase Order</div>

        <!-- Content Section -->
        <div class="content">
            <div class="salutation" style="margin-top: 10px;">Sir,</div>

            <div class="intro-text">
                We are pleased to place an order to supply the below mentioned items for our Educational Institution
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="sr-col">Sr.</th>
                        <th class="desc-col">DESCRIPTION</th>
                        <th class="qty-col">QTY</th>
                        <th class="rate-col">NET RATE (₹)</th>
                        <th class="tax-col">TAX (₹)</th>
                        <th class="amount-col">AMOUNT (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->items as $index => $item)
                        <tr>
                            <td>
                                <div class="field-value">{{ $index + 1 }}</div>
                            </td>
                            <td>
                                <div class="field-value">
                                    {{ $item->item_name }}
                                    @if($item->category)
                                        <span class="item-category">({{ $item->category->name }}@if($item->subcategory) > {{ $item->subcategory->name }}@endif)</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="field-value">{{ $item->qty }}</div>
                            </td>
                            <td style="text-align: right;">
                                <div class="field-value">{{ number_format($item->unit_price, 2) }}</div>
                            </td>
                            <td style="text-align: right;">
                                <div class="field-value">({{ number_format($item->gst_percentage, 0) }}%) {{ number_format($item->gst, 2) }}</div>
                            </td>
                            <td style="text-align: right;">
                                <div class="field-value">{{ number_format($item->total, 2) }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td class="label">Sub Total</td>
                        <td class="value">₹{{ number_format($purchaseOrder->sub_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total GST</td>
                        <td class="value">₹{{ number_format($purchaseOrder->tax, 2) }}</td>
                    </tr>
                    @if($purchaseOrder->shipping > 0)
                    <tr>
                        <td class="label">Shipping</td>
                        <td class="value">₹{{ number_format($purchaseOrder->shipping, 2) }}</td>
                    </tr>
                    @endif
                    @if($purchaseOrder->other > 0)
                    <tr>
                        <td class="label">Other</td>
                        <td class="value">₹{{ number_format($purchaseOrder->other, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label grand-total">Grand Total</td>
                        <td class="value grand-total">₹{{ number_format($purchaseOrder->grand_total, 2) }}</td>
                    </tr>
                </table>
                <div class="amount-in-words">
                    in Words: {{ \App\Helpers\MiscHelper::amountToWords($purchaseOrder->grand_total) }}
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <div class="signature-section">
                <div class="college-signature">
                    For, {{ $setting ? $setting->company_name : 'SDJ International College' }}
                </div>
                <div class="authorized-signature">
                    <h4>Authorized Signatory</h4>
                    <div class="signature-line"></div>
                </div>
            </div>

            @if($purchaseOrder->notes || $purchaseOrder->terms_and_conditions)
            <div class="notes">
                <h4>Note :</h4>
                <ol>
                    @if($purchaseOrder->notes)
                        @foreach(explode("\n", $purchaseOrder->notes) as $noteLine)
                            @if(trim($noteLine) !== '')
                                <li>{{ trim($noteLine) }}</li>
                            @endif
                        @endforeach
                    @endif
                    @if($purchaseOrder->terms_and_conditions)
                        @foreach(explode("\n", $purchaseOrder->terms_and_conditions) as $termLine)
                            @if(trim($termLine) !== '')
                                <li>{{ trim($termLine) }}</li>
                            @endif
                        @endforeach
                    @endif
                    @if(!$purchaseOrder->notes && !$purchaseOrder->terms_and_conditions)
                        <li>Rates mentioned above are inclusive of GST, no other charges.</li>
                        <li>Delivery at School / College premises between 9:00 am to 5:00 pm.</li>
                        <li>Bill to {{ $setting ? $setting->company_name : 'SDJ International College' }}, Surat.</li>
                        <li>Please send challan or Bill with material and mention our Purchase Order No. & Date.</li>
                    @endif
                </ol>
            </div>
            @endif

            <div class="acceptance-section">
                <div class="acceptance-field">
                    <label>Prepared By :</label>
                    <div class="field-value"></div>
                </div>
                <div class="acceptance-field">
                    <label>Checked By :</label>
                    <div class="field-value"></div>
                </div>
                <div class="acceptance-field">
                    <label>SDJ Group</label>
                </div>
            </div>
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