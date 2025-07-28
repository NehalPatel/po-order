@extends('layouts.admin')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Purchase Order Management</h1>
            <p class="text-gray-600 mt-1">Review and manage all purchase orders</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create New PO
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Search Filters</h3>
                 <form method="GET" action="{{ route('purchase-orders.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="po_number" class="block text-sm font-medium text-gray-700 mb-1">PO Number</label>
                <input type="text" id="po_number" name="po_number" value="{{ request('po_number') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Search by PO number">
            </div>
            <div>
                <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                <input type="text" id="vendor" name="vendor" value="{{ request('vendor') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Search by vendor name">
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
                         <div>
                 <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                 <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
             </div>
             <div>
                 <label for="created_by" class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                 <select id="created_by" name="created_by"
                         class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                     <option value="">All Users</option>
                     @foreach($users as $user)
                         <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                             {{ $user->name }}
                         </option>
                     @endforeach
                 </select>
             </div>
                         <div class="md:col-span-5 flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- PO Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Purchase Orders</h3>
        </div>

        @if($purchaseOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                                         <thead class="bg-gray-50">
                         <tr>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Date</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grand Total</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                         </tr>
                     </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchaseOrders as $purchaseOrder)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchaseOrder->po_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchaseOrder->vendor->company_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ optional($purchaseOrder->po_date)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₹{{ number_format($purchaseOrder->grand_total, 2) }}</td>
                                                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $purchaseOrder->status === 'approved' ? 'bg-green-100 text-green-800' : ($purchaseOrder->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                         {{ ucfirst($purchaseOrder->status) }}
                                     </span>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                     {{ $purchaseOrder->user ? $purchaseOrder->user->name : 'Unknown' }}
                                 </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                           title="View Purchase Order">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @php
                                            $currentUser = Auth::user();
                                            $isAdmin = $currentUser->hasRole('Admin');
                                            $isCreator = $purchaseOrder->user_id === $currentUser->id;
                                            $canEdit = ($purchaseOrder->status === 'draft' && ($isAdmin || $isCreator)) ||
                                                      ($purchaseOrder->status === 'approved' && $isAdmin);
                                        @endphp

                                        @if($canEdit)
                                            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}"
                                               class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                               title="Edit Purchase Order">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this purchase order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                                    title="Delete Purchase Order">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
             <div class="px-6 py-4">
                {{ $purchaseOrders->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No purchase orders found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first purchase order.</p>
                <div class="mt-6">
                    <a href="{{ route('purchase-orders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Create Purchase Order
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection