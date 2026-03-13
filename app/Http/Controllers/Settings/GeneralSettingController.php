<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralSettingController extends Controller
{
    public function index(): View
    {
        $settings = GeneralSetting::instance();

        return view('settings.general-settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tax_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'service_charge_percentage' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        GeneralSetting::instance()->update($validated);

        return redirect()->route('admin.settings.general.index')
            ->with('success', 'Pengaturan umum berhasil disimpan.');
    }
}
