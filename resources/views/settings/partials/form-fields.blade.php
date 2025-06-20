<div class="mb-4">
    <label for="company_name" class="block font-medium">Company Name</label>
    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name ?? '') }}" class="form-input w-full" />
    @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="street_address" class="block font-medium">Street Address</label>
    <input type="text" id="street_address" name="street_address" value="{{ old('street_address', $setting->street_address ?? '') }}" class="form-input w-full" />
    @error('street_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="city" class="block font-medium">City</label>
    <input type="text" id="city" name="city" value="{{ old('city', $setting->city ?? '') }}" class="form-input w-full" />
    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="state" class="block font-medium">State</label>
    <input type="text" id="state" name="state" value="{{ old('state', $setting->state ?? '') }}" class="form-input w-full" />
    @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="zipcode" class="block font-medium">Zipcode</label>
    <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', $setting->zipcode ?? '') }}" class="form-input w-full" />
    @error('zipcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="phone" class="block font-medium">Phone</label>
    <input type="text" id="phone" name="phone" value="{{ old('phone', $setting->phone ?? '') }}" class="form-input w-full" />
    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="website" class="block font-medium">Website</label>
    <input type="text" id="website" name="website" value="{{ old('website', $setting->website ?? '') }}" class="form-input w-full" />
    @error('website') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="email" class="block font-medium">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $setting->email ?? '') }}" class="form-input w-full" />
    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div> 