<?php

namespace App\Http\Controllers;

use App\Models\ShipToAddress;
use Illuminate\Http\Request;

class ShipToAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipToAddresses = ShipToAddress::paginate(10);
        return view('ship-to-addresses.index', compact('shipToAddresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ship-to-addresses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validatedData['team_id'] = auth()->user()->currentTeam->id;

        ShipToAddress::create($validatedData);

        return redirect()->route('ship-to-addresses.index')->with('success', 'Address created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipToAddress $shipToAddress)
    {
        // Not typically needed for this kind of resource, redirect to edit
        return redirect()->route('ship-to-addresses.edit', $shipToAddress);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipToAddress $shipToAddress)
    {
        return view('ship-to-addresses.edit', ['address' => $shipToAddress]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipToAddress $shipToAddress)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $shipToAddress->update($validatedData);

        return redirect()->route('ship-to-addresses.index')->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipToAddress $shipToAddress)
    {
        try {
            $shipToAddress->delete();
            return redirect()->route('ship-to-addresses.index')->with('success', 'Address deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('ship-to-addresses.index')->with('error', 'Cannot delete address because it is associated with a purchase order.');
        }
    }
}
