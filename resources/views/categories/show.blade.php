@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Category Details</h1>
        <p class="text-gray-600 mt-1">View category information and its subcategories.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Category Information</h3>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $category->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $category->description ?: 'No description provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Subcategories</h3>
                <p class="text-sm text-gray-600">Subcategories under this category</p>
            </div>
            @if($category->subcategories->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($category->subcategories as $subcat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subcat->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subcat->description ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No subcategories found</h3>
                    <p class="mt-1 text-sm text-gray-500">This category doesn't have any subcategories yet.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('categories.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Back to List</a>
        <a href="{{ route('categories.edit', $category) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Edit Category</a>
    </div>
</div>
@endsection 