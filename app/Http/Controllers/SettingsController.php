<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    // Display a listing of the settings for the current team
    public function index()
    {
        $teamId = Auth::user()->currentTeam->id;
        $settings = Setting::where('team_id', $teamId)->get();
        return view('settings.index', compact('settings'));
    }

    // Show the form for creating a new setting
    public function create()
    {
        return view('settings.create');
    }

    // Store a newly created setting in storage
    public function store(Request $request)
    {
        $teamId = Auth::user()->currentTeam->id;
        $data = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        $data['team_id'] = $teamId;
        Setting::create($data);
        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    // Display the specified setting
    public function show(Setting $setting)
    {
        return view('settings.show', compact('setting'));
    }

    // Show the form for editing the specified setting
    public function edit(Setting $setting)
    {
        return view('settings.edit', compact('setting'));
    }

    // Update the specified setting in storage
    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo && \Storage::disk('public')->exists($setting->logo)) {
                \Storage::disk('public')->delete($setting->logo);
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        $setting->update($data);
        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    // Remove the specified setting from storage
    public function destroy(Setting $setting)
    {
        $setting->delete();
        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }
}