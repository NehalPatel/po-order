@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600 mt-2">Here's what's happening with your account today.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Purchase Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Purchase Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\PurchaseOrder::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Vendors -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vendors</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Vendor::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Value of POs -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total PO Value</p>
                    <p class="text-2xl font-semibold text-gray-900">₹{{ number_format(\App\Models\PurchaseOrder::sum('grand_total'), 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending POs -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-semibold text-orange-600">{{ \App\Models\PurchaseOrder::whereIn('status', ['draft', 'sent'])->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PO Status Distribution -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- PO Status Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Order Status</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Draft</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \App\Models\PurchaseOrder::where('status', 'draft')->count() }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php $draftPercentage = \App\Models\PurchaseOrder::count() > 0 ? (\App\Models\PurchaseOrder::where('status', 'draft')->count() / \App\Models\PurchaseOrder::count()) * 100 : 0; @endphp
                        <div class="bg-gray-600 h-2.5 rounded-full" style="width: {{ $draftPercentage }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Sent</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \App\Models\PurchaseOrder::where('status', 'sent')->count() }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php $sentPercentage = \App\Models\PurchaseOrder::count() > 0 ? (\App\Models\PurchaseOrder::where('status', 'sent')->count() / \App\Models\PurchaseOrder::count()) * 100 : 0; @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $sentPercentage }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Approved</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \App\Models\PurchaseOrder::where('status', 'approved')->count() }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php $approvedPercentage = \App\Models\PurchaseOrder::count() > 0 ? (\App\Models\PurchaseOrder::where('status', 'approved')->count() / \App\Models\PurchaseOrder::count()) * 100 : 0; @endphp
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $approvedPercentage }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Completed</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \App\Models\PurchaseOrder::where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php $completedPercentage = \App\Models\PurchaseOrder::count() > 0 ? (\App\Models\PurchaseOrder::where('status', 'completed')->count() / \App\Models\PurchaseOrder::count()) * 100 : 0; @endphp
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $completedPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Purchase Orders -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Recent Purchase Orders</h3>
            <a href="{{ route('purchase-orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
        </div>
        
        @if(\App\Models\PurchaseOrder::count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(\App\Models\PurchaseOrder::with('vendor')->latest()->take(5)->get() as $po)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $po->po_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $po->vendor->company_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ optional($po->po_date)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₹{{ number_format($po->grand_total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $po->status === 'approved' ? 'bg-green-100 text-green-800' : ($po->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-4 text-center text-gray-500">
                No purchase orders found. <a href="{{ route('purchase-orders.create') }}" class="text-blue-600 hover:text-blue-800">Create your first purchase order</a>.
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('purchase-orders.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">New Purchase Order</p>
                    <p class="text-sm text-gray-500">Create a new PO</p>
                </div>
            </a>

            <a href="{{ route('vendors.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Add Vendor</p>
                    <p class="text-sm text-gray-500">Register a new vendor</p>
                </div>
            </a>

            <a href="{{ route('settings.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Company Settings</p>
                    <p class="text-sm text-gray-500">Update company info</p>
                </div>
            </a>

            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Add Address</p>
                    <p class="text-sm text-gray-500">New shipping address</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
