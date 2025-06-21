<div class="grid grid-cols-6 gap-6">
    <div class="col-span-6 sm:col-span-4">
        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $vendor->company_name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="col-span-6 sm:col-span-4">
        <label for="contact_person_name" class="block text-sm font-medium text-gray-700">Contact Person</label>
        <input type="text" name="contact_person_name" id="contact_person_name" value="{{ old('contact_person_name', $vendor->contact_person_name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <div class="col-span-6">
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <input type="text" name="address" id="address" value="{{ old('address', $vendor->address ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="col-span-6 sm:col-span-2">
        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $vendor->city ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="col-span-6 sm:col-span-2">
        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
        <input type="text" name="state" id="state" value="{{ old('state', $vendor->state ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="col-span-6 sm:col-span-2">
        <label for="zipcode" class="block text-sm font-medium text-gray-700">Zipcode</label>
        <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode', $vendor->zipcode ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="col-span-6 sm:col-span-3">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $vendor->email ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="col-span-6 sm:col-span-3">
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <div class="col-span-6">
        <label for="referenced_by" class="block text-sm font-medium text-gray-700">Referenced By</label>
        <input type="text" name="referenced_by" id="referenced_by" value="{{ old('referenced_by', $vendor->referenced_by ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
</div>