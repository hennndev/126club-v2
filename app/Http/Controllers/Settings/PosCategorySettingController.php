<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\PosCategorySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosCategorySettingController extends Controller
{
    public function index(): View
    {
        $knownTypes = InventoryItem::distinct()->orderBy('category_type')->pluck('category_type');
        $settings = PosCategorySetting::all()->keyBy('category_type');

        return view('settings.pos-categories', compact('knownTypes', 'settings'));
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'categories' => 'present|array',
            'categories.*.source' => 'required|in:bom,inventory,both',
            'categories.*.preparation_location' => 'required|in:kitchen,bar,direct',
        ]);

        foreach ($validated['categories'] as $categoryType => $data) {
            PosCategorySetting::updateOrCreate(
                ['category_type' => $categoryType],
                [
                    'show_in_pos' => isset($request->input('show_in_pos', [])[$categoryType]),
                    'source' => $data['source'],
                    'preparation_location' => $data['preparation_location'],
                ]
            );
        }

        PosCategorySetting::clearCache();

        return redirect()->route('admin.settings.pos-categories.index')
            ->with('success', 'Pengaturan POS berhasil disimpan.');
    }
}
