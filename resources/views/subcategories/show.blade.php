@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Subcategory Details</h1>
        <p class="text-gray-600 mt-1">View subcategory information and details.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Subcategory Information</h3>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $subcategory->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($subcategory->category)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $subcategory->category->name }}
                            </span>
                        @else
                            <span class="text-gray-400">No category assigned</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $subcategory->description ?: 'No description provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('subcategories.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Back to List</a>
        <a href="{{ route('subcategories.edit', $subcategory) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Edit Subcategory</a>
    </div>
</div>
@endsection 