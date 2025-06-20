@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        {{ __('Settings List') }}
    </h2>
    <a href="{{ route('settings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Create New Setting</a>
    @if(session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Company Name</th>
                <th class="py-2">Email</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
                <tr>
                    <td class="py-2">{{ $setting->company_name }}</td>
                    <td class="py-2">{{ $setting->email }}</td>
                    <td class="py-2">
                        <a href="{{ route('settings.show', $setting) }}" class="text-blue-600">View</a> |
                        <a href="{{ route('settings.edit', $setting) }}" class="text-yellow-600">Edit</a> |
                        <form action="{{ route('settings.destroy', $setting) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 