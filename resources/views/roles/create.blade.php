@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-xl mx-auto">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Role</h1>
        <p class="text-gray-600 mt-1">Add a new role and assign permissions.</p>
    </div>
    <form action="{{ route('roles.store') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        @csrf
        <div>
            <label for="name" class="block font-medium">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input w-full mt-1" required />
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block font-medium mb-2">Permissions</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($permissions as $permission)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-checkbox" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('permissions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('roles.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Create Role</button>
        </div>
    </form>
</div>
@endsection