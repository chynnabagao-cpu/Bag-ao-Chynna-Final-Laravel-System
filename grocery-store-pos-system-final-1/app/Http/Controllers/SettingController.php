<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $gcash_qr = Setting::get('gcash_qr_path');
        $store_name = Setting::get('store_name', 'FreshMart Enterprise');
        $store_address = Setting::get('store_address', '123 Market St, Metro Manila');
        $store_contact = Setting::get('store_contact', '+63 912 345 6789');

        return view('settings.index', compact('gcash_qr', 'store_name', 'store_address', 'store_contact'));
    }

    public function updateStoreProfile(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'required|string|max:500',
            'store_contact' => 'required|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Store Profile updated successfully.');
    }

    public function updateGcashQr(Request $request)
    {
        $request->validate([
            'gcash_qr' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gcash_qr')) {
            // Delete old QR if exists
            $oldPath = Setting::get('gcash_qr_path');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('gcash_qr')->store('settings', 'public');
            Setting::set('gcash_qr_path', $path);
        }

        return back()->with('success', 'GCash QR Code updated successfully.');
    }
}
