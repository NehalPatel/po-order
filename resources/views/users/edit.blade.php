@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-xl mx-auto">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
        <p class="text-gray-600 mt-1">Update user details.</p>
    </div>
    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block font-medium">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input w-full mt-1" required />
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="email" class="block font-medium">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input w-full mt-1" required />
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="password" class="block font-medium">Password <span class="text-xs text-gray-500">(leave blank to keep current)</span></label>
            <input type="password" id="password" name="password" class="form-input w-full mt-1" />
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block font-medium">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input w-full mt-1" />
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Update User</button>
        </div>
    </form>
</div>
@endsection