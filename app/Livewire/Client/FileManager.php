<?php

namespace App\Livewire\Client;

use App\Models\Subdomain;
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileManager extends Component
{
    public Subdomain $subdomain;
    public string $requestedPath = '';
    public array $breadcrumbs = [];
    public array $folders = [];
    public array $files = [];
    
    // Status properties for reactive deletion modal
    public string $deletePath = '';
    public string $deleteName = '';
    public bool $deleteIsDir = false;

    // Toast/Alert session states
    public string $successMessage = '';
    public string $errorMessage = '';

    public function mount(Subdomain $subdomain)
    {
        $this->subdomain = $subdomain;
        $this->loadDirectory();
    }

    /**
     * Scan and load folders and files inside the resolved secure path.
     */
    public function loadDirectory()
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        $basePath = realpath($this->subdomain->doc_root);
        if (!$basePath || !is_dir($basePath)) {
            $this->errorMessage = 'Direktori root proyek tidak ditemukan di server.';
            return;
        }

        // Safety Traversal Sanitization
        $path = str_replace(['..', '\\'], ['', '/'], $this->requestedPath);
        $path = trim($path, '/');
        $this->requestedPath = $path;

        // Resolve absolute target path
        $fullPath = realpath($basePath . ($this->requestedPath !== '' ? '/' . $this->requestedPath : ''));

        // Traversal Check
        if (!$fullPath || !Str::startsWith($fullPath, $basePath)) {
            abort(403, 'Aksi tidak sah: Penelusuran di luar batas direktori proyek.');
        }

        $this->folders = [];
        $this->files = [];

        if (is_dir($fullPath)) {
            $contents = scandir($fullPath);
            foreach ($contents as $itemName) {
                if ($itemName === '.' || $itemName === '..') {
                    continue;
                }

                // Hide sensitive configuration files inside the root directory
                if ($this->requestedPath === '') {
                    if ($itemName === '.env' || $itemName === '.git') {
                        continue;
                    }
                }

                $itemFullPath = $fullPath . '/' . $itemName;
                $relativeItemPath = $this->requestedPath !== '' ? $this->requestedPath . '/' . $itemName : $itemName;

                $isDir = is_dir($itemFullPath);
                $sizeBytes = $isDir ? 0 : filesize($itemFullPath);
                $lastModified = filemtime($itemFullPath);

                // Format size
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
                    $this->folders[] = $itemData;
                } else {
                    $this->files[] = $itemData;
                }
            }
        }

        // Sort items alphabetically by name
        usort($this->folders, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        usort($this->files, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        // Map breadcrumbs
        $this->breadcrumbs = [];
        if ($this->requestedPath !== '') {
            $parts = explode('/', $this->requestedPath);
            $currentAccumulated = '';
            foreach ($parts as $part) {
                $currentAccumulated = $currentAccumulated !== '' ? $currentAccumulated . '/' . $part : $part;
                $this->breadcrumbs[] = [
                    'name' => $part,
                    'path' => $currentAccumulated,
                ];
            }
        }
    }

    /**
     * Navigate to a subfolder instantly without reloading the browser.
     */
    public function navigateTo(string $path)
    {
        $this->requestedPath = $path;
        $this->loadDirectory();
    }

    /**
     * Go up one directory level.
     */
    public function goUp()
    {
        if ($this->requestedPath !== '') {
            $parts = explode('/', $this->requestedPath);
            array_pop($parts);
            $this->requestedPath = implode('/', $parts);
            $this->loadDirectory();
        }
    }

    /**
     * Trigger modal confirmation for deleting folder/file.
     */
    public function confirmDelete(string $path, string $name, bool $isDir)
    {
        $this->deletePath = $path;
        $this->deleteName = $name;
        $this->deleteIsDir = $isDir;
    }

    /**
     * Reset modal state.
     */
    public function closeDeleteModal()
    {
        $this->deletePath = '';
        $this->deleteName = '';
        $this->deleteIsDir = false;
    }

    /**
     * Securely delete file or folder physically from the server doc root.
     */
    public function deleteItem()
    {
        if (empty($this->deletePath)) {
            return;
        }

        $basePath = realpath($this->subdomain->doc_root);
        if (!$basePath || !is_dir($basePath)) {
            $this->errorMessage = 'Direktori root proyek tidak ditemukan di server.';
            return;
        }

        // Safety sanitization
        $target = str_replace(['..', '\\'], ['', '/'], $this->deletePath);
        $target = trim($target, '/');

        // Resolve absolute target path
        $fullPath = realpath($basePath . '/' . $target);

        // Strict Traversal Protection
        if (!$fullPath || !Str::startsWith($fullPath, $basePath) || $fullPath === $basePath) {
            $this->errorMessage = 'Aksi tidak sah: Menghapus di luar batas direktori aman tidak diizinkan.';
            $this->closeDeleteModal();
            return;
        }

        $itemName = basename($fullPath);

        // Critical safety check: Protect system routing config
        if ($itemName === '.htaccess' || $itemName === '.env') {
            $this->errorMessage = 'Maaf, berkas konfigurasi sistem penting seperti .htaccess atau .env tidak boleh dihapus.';
            $this->closeDeleteModal();
            return;
        }

        try {
            if (is_dir($fullPath)) {
                File::deleteDirectory($fullPath);
                $type = 'Direktori';
            } else {
                File::delete($fullPath);
                $type = 'Berkas';
            }

            $this->successMessage = "{$type} '{$itemName}' berhasil dihapus secara permanen.";
            $this->closeDeleteModal();
            $this->loadDirectory();

            // Dispatch browser event to trigger client-side toast notifications
            $this->dispatch('show-toast', $this->successMessage);

        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal menghapus berkas: ' . $e->getMessage();
            $this->closeDeleteModal();
        }
    }

    public function render()
    {
        return view('livewire.client.file-manager');
    }
}
