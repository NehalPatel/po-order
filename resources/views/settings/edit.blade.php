@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Edit Setting</h1>
      <p class="text-gray-600 mt-1">Update the details for {{ $setting->company_name }}.</p>
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

    <form action="{{ route('settings.update', $setting) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="space-y-6">
                @include('settings.partials.form-fields', ['setting' => $setting])
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('settings.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Update Setting</button>
            </div>
        </div>
    </form>
</div>
@endsection