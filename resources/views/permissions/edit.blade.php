@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-xl mx-auto">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Permission</h1>
        <p class="text-gray-600 mt-1">Update permission details.</p>
    </div>
    <form action="{{ route('permissions.update', $permission) }}" method="POST" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block font-medium">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" class="form-input w-full mt-1" required />
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('permissions.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Update Permission</button>
        </div>
    </form>
</div>
@endsection