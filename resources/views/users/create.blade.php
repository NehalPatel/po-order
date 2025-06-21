@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-xl mx-auto">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create User</h1>
        <p class="text-gray-600 mt-1">Add a new user to the application.</p>
    </div>
    <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        @csrf
        <div>
            <label for="name" class="block font-medium">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input w-full mt-1" required />
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="email" class="block font-medium">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input w-full mt-1" required />
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="password" class="block font-medium">Password</label>
            <input type="password" id="password" name="password" class="form-input w-full mt-1" required />
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block font-medium">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input w-full mt-1" required />
        </div>
        <div>
            <label class="block font-medium mb-2">Roles</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($roles as $role)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-checkbox" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('roles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Create User</button>
        </div>
    </form>
</div>
@endsection