<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $filename = $this->generateFilename($file);
        $path = $file->storeAs($folder, $filename, 'public');
        return $path;
    }

    public function uploadMultiple(array $files, string $folder = 'uploads'): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->upload($file, $folder);
            }
        }
        return $paths;
    }

    public function delete(?string $path): bool
    {
        if (!$path) {
            return false;
        }
        return Storage::disk('public')->delete($path);
    }

    public function deleteMultiple(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }

    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);

        return "{$name}-{$timestamp}-{$random}.{$extension}";
    }

    public function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        return Storage::disk('public')->url($path);
    }

    /**
     * Download an image from a URL and store it locally
     */
    public function uploadFromUrl(string $url, string $folder = 'uploads'): ?string
    {
        try {
            // Validate URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return null;
            }

            // Get image content with timeout
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (compatible; ImageDownloader/1.0)',
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $imageContent = @file_get_contents($url, false, $context);

            if ($imageContent === false) {
                return null;
            }

            // Determine file extension from URL or content type
            $extension = $this->getExtensionFromUrl($url);

            // If no extension found, try to detect from content
            if (!$extension) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageContent);
                $extension = $this->getExtensionFromMime($mimeType);
            }

            if (!$extension || !in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                return null;
            }

            // Generate unique filename
            $timestamp = now()->format('YmdHis');
            $random = Str::random(8);
            $filename = "url-image-{$timestamp}-{$random}.{$extension}";

            // Store the file
            $path = "{$folder}/{$filename}";
            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to upload image from URL: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get file extension from URL
     */
    protected function getExtensionFromUrl(string $url): ?string
    {
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['path'])) {
            return null;
        }

        $extension = strtolower(pathinfo($parsedUrl['path'], PATHINFO_EXTENSION));

        // Handle common variations
        if ($extension === 'jpeg') {
            return 'jpg';
        }

        return $extension ?: null;
    }

    /**
     * Get file extension from MIME type
     */
    protected function getExtensionFromMime(string $mimeType): ?string
    {
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        return $mimeToExt[$mimeType] ?? null;
    }
}
