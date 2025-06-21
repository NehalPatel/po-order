@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Create New Vendor</h1>
      <p class="text-gray-600 mt-1">Fill out the form below to add a new vendor.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendors.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="space-y-6">
                 @include('vendors.partials.form-fields')
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('vendors.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Create Vendor</button>
            </div>
        </div>
    </form>
</div>
@endsection