<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
  // HALAMAN AREA MANAGEMENT
  public function index()
  {
    $areas = Area::orderBy('sort_order')->orderBy('name')->get();
    return view('areas.index', compact('areas'));
  }

  // CREATE AREA
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:255|unique:areas,code',
      'name' => 'required|string|max:255',
      'capacity' => 'nullable|integer|min:0',
      'description' => 'nullable|string',
      'sort_order' => 'nullable|integer',
    ]);
    $validated['is_active'] = $request->has('is_active');
    $validated['sort_order'] = $validated['sort_order'] ?? 0;
    Area::create($validated);
    return redirect()->route('admin.areas.index')->with('success', 'Area berhasil ditambahkan!');
  }

  // UPDATE AREA
  public function update(Request $request, Area $area)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:255|unique:areas,code,' . $area->id,
      'name' => 'required|string|max:255',
      'capacity' => 'nullable|integer|min:0',
      'description' => 'nullable|string',
      'sort_order' => 'nullable|integer',
    ]);
    $validated['is_active'] = $request->has('is_active');
    $validated['sort_order'] = $validated['sort_order'] ?? 0;
    $area->update($validated);
    return redirect()->route('admin.areas.index')->with('success', 'Area berhasil diupdate!');
  }

  // DELETE AREA
  public function destroy(Area $area)
  {
    $area->delete();
    return redirect()->route('admin.areas.index')->with('success', 'Area berhasil dihapus!');
  }
}
