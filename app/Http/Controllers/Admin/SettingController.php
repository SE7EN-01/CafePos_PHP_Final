<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::getAll();

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exchange_rate_khr' => ['required', 'numeric', 'min:1000', 'max:10000'],
            'default_currency' => ['required', 'in:khr,usd'],
            'payment_cash_enabled' => ['nullable'],
            'payment_khqr_enabled' => ['nullable'],
            'bakong_account_id' => ['nullable', 'string', 'max:100'],
            'bakong_merchant_name' => ['nullable', 'string', 'max:100'],
            'bakong_merchant_city' => ['nullable', 'string', 'max:100'],
            'cafe_name' => ['required', 'string', 'max:100'],
            'cafe_tagline' => ['nullable', 'string', 'max:150'],
            'cafe_phone' => ['nullable', 'string', 'max:50'],
            'cafe_address' => ['nullable', 'string', 'max:255'],
            'tax_rate_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'receipt_footer_text' => ['nullable', 'string', 'max:255'],
            'pos_default_language' => ['required', 'in:en,km'],
            'low_stock_threshold' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        // Save checkboxes explicitly
        $validated['payment_cash_enabled'] = $request->has('payment_cash_enabled') ? '1' : '0';
        $validated['payment_khqr_enabled'] = $request->has('payment_khqr_enabled') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('status', 'settings-updated');
    }
}
