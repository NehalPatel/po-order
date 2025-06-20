@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        {{ __('Create Setting') }}
    </h2>
    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        @include('settings.partials.form-fields')
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
    </form>
</div>
@endsection 