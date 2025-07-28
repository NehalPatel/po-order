<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = \App\Models\Subcategory::with('category')->paginate(10);
        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        \App\Models\Subcategory::create($validated);
        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Subcategory $subcategory)
    {
        $subcategory->load('category');
        return view('subcategories.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Subcategory $subcategory)
    {
        $categories = \App\Models\Category::all();
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Subcategory $subcategory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $subcategory->update($validated);
        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
