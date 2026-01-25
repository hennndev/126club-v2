<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('category');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category_type', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('stock_filter') && $request->stock_filter == 'low') {
            $query->whereColumn('stock_quantity', '<=', 'threshold');
        }

        $items = $query
        ->whereNotIn('category_type', ['food', 'bar'])
        ->orderBy('name')->get();
        
        $totalItems = InventoryItem::count();
        $totalStockValue = InventoryItem::selectRaw('SUM(price * stock_quantity) as total')->value('total') ?? 0;
        $lowStockCount = InventoryItem::whereColumn('stock_quantity', '<=', 'threshold')->count();

        return view('inventory.index', compact(
            'items',
            'totalItems',
            'totalStockValue',
            'lowStockCount',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'name' => 'required|string|max:255',
            'category_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        try {
            InventoryItem::create($validated);
            return redirect()->route('admin.inventory.index')
                ->with('success', 'Inventory item berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan item: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'name' => 'required|string|max:255',
            'category_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        try {
            $inventory->update($validated);
            return redirect()->route('admin.inventory.index')
                ->with('success', 'Inventory item berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate item: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(InventoryItem $inventory)
    {
        try {
            $inventory->delete();
            return redirect()->route('admin.inventory.index')
                ->with('success', 'Inventory item berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus item: ' . $e->getMessage()]);
        }
    }

    public function updateThreshold(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:inventory_items,id',
            'items.*.threshold' => 'required|integer|min:0',
        ]);

        try {
            foreach ($validated['items'] as $itemData) {
                InventoryItem::where('id', $itemData['id'])->update([
                    'threshold' => $itemData['threshold']
                ]);
            }
            return redirect()->route('admin.inventory.index')
                ->with('success', 'Threshold berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate threshold: ' . $e->getMessage()]);
        }
    }
}
