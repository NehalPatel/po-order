@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        {{ __('Setting Details') }}
    </h2>
    <div class="mb-4"><strong>Company Name:</strong> {{ $setting->company_name }}</div>
    <div class="mb-4"><strong>Address:</strong> {{ $setting->address }}</div>
    <div class="mb-4"><strong>Phone:</strong> {{ $setting->phone }}</div>
    @if($setting->logo)
        <div class="mb-4">
            <strong>Logo:</strong><br>
            <img src="{{ $setting->logo_url }}" alt="Company Logo" class="w-32 h-32 object-contain border border-gray-300 rounded mt-2">
        </div>
    @endif
    <div class="mb-4"><strong>Website:</strong> {{ $setting->website }}</div>
    <div class="mb-4"><strong>Email:</strong> {{ $setting->email }}</div>
    <a href="{{ route('settings.edit', $setting) }}" class="bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
    <a href="{{ route('settings.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
</div>
@endsection