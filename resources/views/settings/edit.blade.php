@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        {{ __('Edit Setting') }}
    </h2>
    <form action="{{ route('settings.update', $setting) }}" method="POST">
        @csrf
        @method('PUT')
        @include('settings.partials.form-fields', ['setting' => $setting])
        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection 