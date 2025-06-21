@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Oops!</strong>
        <span class="block sm:inline">There were some problems with your input.</span>
        <ul class="mt-3 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Location Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $address->name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <p class="mt-1 text-xs text-gray-500">A friendly name for this address (e.g., "Main Warehouse", "Downtown Office").</p>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
        <input type="text" name="address" id="address" value="{{ old('address', $address->address ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
            <input type="text" name="city" id="city" value="{{ old('city', $address->city ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
            <input type="text" name="state" id="state" value="{{ old('state', $address->state ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="zipcode" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
            <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode', $address->zipcode ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $address->contact_person ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
    </div>
</div>