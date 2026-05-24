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
                
                // Define the public uploads path - direct access without symlinks
                $uploadPath = public_path('uploads/settings');
                
                // Ensure the directory exists
                if (!\Illuminate\Support\Facades\File::isDirectory($uploadPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true, true);
                }

                $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                
                $dbPath = 'uploads/settings/' . $filename;
                
                // Delete old file if exists and not the default one
                $oldPath = Setting::get('qris_image_path');
                if ($oldPath && $oldPath !== 'images/qris_static.png' && strpos($oldPath, 'uploads/') === 0) {
                    $fullOldPath = public_path($oldPath);
                    if (\Illuminate\Support\Facades\File::exists($fullOldPath)) {
                        \Illuminate\Support\Facades\File::delete($fullOldPath);
                    }
                }

                Setting::set('qris_image_path', $dbPath);

                return back()->with('success', 'QRIS image updated successfully.');
            }
            
            return back()->with('error', 'No file was detected in the request.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QRIS Upload Error: ' . $e->getMessage());
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
}
