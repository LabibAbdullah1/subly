<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    /**
     * Display a secure listing of files and folders in the subdomain's document root.
     */
    public function index(Request $request, Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        $basePath = realpath($subdomain->doc_root);
        if (!$basePath || !is_dir($basePath)) {
            return redirect()->route('client.portal', $subdomain->id)
                ->withErrors(['file_manager' => 'Direktori root proyek tidak ditemukan di server.']);
        }

        // Get relative path from query parameter
        $requestedPath = $request->query('path', '');
        
        // Strict safety check: prevent backslashes and directory traversal
        $requestedPath = str_replace(['..', '\\'], ['', '/'], $requestedPath);
        $requestedPath = trim($requestedPath, '/');

        // Resolve absolute target path
        $fullPath = realpath($basePath . ($requestedPath !== '' ? '/' . $requestedPath : ''));

        // Traversal Protection: Ensure resolved path is strictly within the subdomain base path
        if (!$fullPath || !Str::startsWith($fullPath, $basePath)) {
            abort(403, 'Aksi tidak sah: Akses di luar batas aman proyek.');
        }

        $folders = [];
        $files = [];

        if (is_dir($fullPath)) {
            $contents = scandir($fullPath);
            foreach ($contents as $itemName) {
                if ($itemName === '.' || $itemName === '..') {
                    continue;
                }

                // Hide sensitive configuration files inside the root directory to protect DB credentials
                if ($requestedPath === '') {
                    if ($itemName === '.env' || $itemName === '.git') {
                        continue;
                    }
                }

                $itemFullPath = $fullPath . '/' . $itemName;
                $relativeItemPath = $requestedPath !== '' ? $requestedPath . '/' . $itemName : $itemName;

                $isDir = is_dir($itemFullPath);
                $sizeBytes = $isDir ? 0 : filesize($itemFullPath);
                $lastModified = filemtime($itemFullPath);

                // Formatting file size
                $formattedSize = '-';
                if (!$isDir) {
                    if ($sizeBytes >= 1073741824) {
                        $formattedSize = round($sizeBytes / 1073741824, 2) . ' GB';
                    } elseif ($sizeBytes >= 1048576) {
                        $formattedSize = round($sizeBytes / 1048576, 2) . ' MB';
                    } elseif ($sizeBytes >= 1024) {
                        $formattedSize = round($sizeBytes / 1024, 2) . ' KB';
                    } else {
                        $formattedSize = $sizeBytes . ' B';
                    }
                }

                $extension = $isDir ? 'folder' : strtolower(pathinfo($itemName, PATHINFO_EXTENSION));

                $itemData = [
                    'name' => $itemName,
                    'path' => $relativeItemPath,
                    'is_dir' => $isDir,
                    'size' => $formattedSize,
                    'size_bytes' => $sizeBytes,
                    'last_modified' => date('d M Y H:i', $lastModified),
                    'extension' => $extension,
                ];

                if ($isDir) {
                    $folders[] = $itemData;
                } else {
                    $files[] = $itemData;
                }
            }
        }

        // Sort items alphabetically by name
        usort($folders, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        usort($files, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        // Map breadcrumbs for easy folder backtracking
        $breadcrumbs = [];
        if ($requestedPath !== '') {
            $parts = explode('/', $requestedPath);
            $currentAccumulated = '';
            foreach ($parts as $part) {
                $currentAccumulated = $currentAccumulated !== '' ? $currentAccumulated . '/' . $part : $part;
                $breadcrumbs[] = [
                    'name' => $part,
                    'path' => $currentAccumulated,
                ];
            }
        }

        return view('client.file_manager.index', compact('subdomain', 'requestedPath', 'breadcrumbs', 'folders', 'files'));
    }

    /**
     * Delete a specific file or folder safely within the subdomain document root.
     */
    public function destroy(Request $request, Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        $request->validate([
            'path' => 'required|string',
        ]);

        $basePath = realpath($subdomain->doc_root);
        if (!$basePath || !is_dir($basePath)) {
            return redirect()->back()->withErrors(['file_manager' => 'Direktori root proyek tidak ditemukan di server.']);
        }

        $requestedItem = $request->input('path');
        
        // Strict safety check: prevent backslashes and directory traversal
        $requestedItem = str_replace(['..', '\\'], ['', '/'], $requestedItem);
        $requestedItem = trim($requestedItem, '/');

        // Resolve absolute item path
        $fullPath = realpath($basePath . '/' . $requestedItem);

        // Strict Traversal Protection: must be inside the base path, and CANNOT delete the root itself
        if (!$fullPath || !Str::startsWith($fullPath, $basePath) || $fullPath === $basePath) {
            abort(403, 'Aksi tidak sah: Menghapus di luar batas direktori aman tidak diizinkan.');
        }

        $itemName = basename($fullPath);

        // Critical safety check: Protect system routing config from accidental deletion
        if ($itemName === '.htaccess' || $itemName === '.env') {
            return redirect()->back()->withErrors(['file_manager' => 'Maaf, berkas konfigurasi sistem penting seperti .htaccess atau .env tidak boleh dihapus.']);
        }

        try {
            if (is_dir($fullPath)) {
                File::deleteDirectory($fullPath);
                $type = 'Direktori';
            } else {
                File::delete($fullPath);
                $type = 'Berkas';
            }

            return redirect()->back()->with('success', "{$type} '{$itemName}' berhasil dihapus secara permanen.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file_manager' => 'Gagal menghapus berkas: ' . $e->getMessage()]);
        }
    }
}
