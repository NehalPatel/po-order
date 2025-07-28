<div class="mb-4">
    <label for="company_name" class="block font-medium">Company Name</label>
    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name ?? '') }}" class="form-input w-full" />
    @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="address" class="block font-medium">Address</label>
    <textarea id="address" name="address" rows="3" class="form-input w-full" placeholder="Enter complete address including street, city, state, and zipcode">{{ old('address', $setting->address ?? '') }}</textarea>
    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label for="phone" class="block font-medium">Phone</label>
    <input type="text" id="phone" name="phone" value="{{ old('phone', $setting->phone ?? '') }}" class="form-input w-full" />
    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>

<div class="mb-4">
    <label for="logo" class="block font-medium">Company Logo</label>
    @if(isset($setting) && $setting->logo)
        <div class="mb-2">
            <img src="{{ $setting->logo_url }}" alt="Current Logo" class="w-32 h-32 object-contain border border-gray-300 rounded">
        </div>
    @endif
    <input type="file" id="logo" name="logo" accept="image/*" class="form-input w-full" />
    <p class="text-sm text-gray-500 mt-1">Upload a logo image (JPEG, PNG, JPG, GIF - max 2MB)</p>
    @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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