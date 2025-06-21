@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $vendor->company_name }}</h1>
                <p class="text-gray-600 mt-1">Vendor Details</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('vendors.edit', $vendor) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Edit</a>
                <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Delete</button>
                </form>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vendor->contact_person_name ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vendor->email }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vendor->phone ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Referenced By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vendor->referenced_by ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $vendor->address }}<br>
                        {{ $vendor->city }}, {{ $vendor->state }} {{ $vendor->zipcode }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('vendors.index') }}" class="text-blue-600 hover:underline">← Back to all vendors</a>
    </div>
</div>
@endsection