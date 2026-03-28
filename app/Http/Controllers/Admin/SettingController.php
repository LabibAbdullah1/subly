<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $qrisImage = Setting::get('qris_image_path', 'images/qris_static.png');
        return view('admin.settings.index', compact('qrisImage'));
    }

    public function updateQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('qris_image')) {
                $file = $request->file('qris_image');
                
                // Ensure the settings directory exists in public disk (essential for some hosting)
                if (!Storage::disk('public')->exists('settings')) {
                    Storage::disk('public')->makeDirectory('settings', 0755, true);
                }

                $path = $file->store('settings', 'public');
                
                if (!$path) {
                    throw new \Exception('Laravel was unable to save the file to the disk.');
                }
                
                // Delete old file if exists and not the default one
                $oldPath = Setting::get('qris_image_path');
                if ($oldPath && $oldPath !== 'images/qris_static.png') {
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                Setting::set('qris_image_path', $path);

                return back()->with('success', 'QRIS image updated successfully.');
            }
            
            return back()->with('error', 'No file was detected in the request.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QRIS Upload Error: ' . $e->getMessage());
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
}
