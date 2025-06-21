@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">User Details</h1>
        <div class="mb-2"><strong>Name:</strong> {{ $user->name }}</div>
        <div class="mb-2"><strong>Email:</strong> {{ $user->email }}</div>
        <div class="mb-2"><strong>Created At:</strong> {{ $user->created_at->format('d-m-Y H:i') }}</div>
        <div class="mb-2"><strong>Updated At:</strong> {{ $user->updated_at->format('d-m-Y H:i') }}</div>
        <div class="mt-6 flex justify-end space-x-2">
            <a href="{{ route('users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Edit</a>
            <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Back</a>
        </div>
    </div>
</div>
@endsection