<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Media
{
    private const ALLOWED_MIMES = [
        'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf',
    ];
    private const MAX_SIZE = 10 * 1024 * 1024; // 10MB
    private const THUMB_WIDTH = 400;
    private const THUMB_HEIGHT = 300;

    /**
     * Handle file upload. Returns media ID or throws exception.
     */
    public static function upload(array $file): int
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Chyba při nahrávání souboru.');
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new \RuntimeException('Soubor je příliš velký (max 10 MB).');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, self::ALLOWED_MIMES, true)) {
            throw new \RuntimeException('Nepodporovaný typ souboru: ' . $mime);
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            default => 'bin',
        };

        $hash = bin2hex(random_bytes(16));
        $filename = $hash . '.' . $ext;
        $yearMonth = date('Y/m');
        $relDir = 'uploads/' . $yearMonth;
        $absDir = UPLOAD_PATH . '/' . $yearMonth;

        if (!is_dir($absDir)) {
            mkdir($absDir, 0755, true);
        }

        $absPath = $absDir . '/' . $filename;
        $relPath = $relDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $absPath)) {
            throw new \RuntimeException('Nepodařilo se uložit soubor.');
        }

        $width = null;
        $height = null;

        // Image processing
        if (str_starts_with($mime, 'image/') && $mime !== 'image/gif') {
            $info = getimagesize($absPath);
            if ($info) {
                $width = $info[0];
                $height = $info[1];
            }

            // Generate thumbnail
            self::createThumbnail($absPath, $absDir . '/thumb_' . $filename, self::THUMB_WIDTH, self::THUMB_HEIGHT);

            // Generate WebP version
            if ($mime !== 'image/webp' && function_exists('imagewebp')) {
                self::convertToWebP($absPath, $absDir . '/' . $hash . '.webp');
            }
        }

        $db = Database::getInstance();
        return $db->insert('zvele_media', [
            'filename' => $filename,
            'original_name' => $file['name'],
            'path' => $relPath,
            'mime_type' => $mime,
            'size' => $file['size'],
            'width' => $width,
            'height' => $height,
            'alt_text' => '',
        ]);
    }

    /**
     * Create thumbnail using GD.
     */
    private static function createThumbnail(string $source, string $dest, int $maxW, int $maxH): void
    {
        $info = getimagesize($source);
        if (!$info) return;

        $srcImg = match ($info['mime']) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            default => null,
        };

        if (!$srcImg) return;

        $srcW = imagesx($srcImg);
        $srcH = imagesy($srcImg);
        $ratio = min($maxW / $srcW, $maxH / $srcH);

        if ($ratio >= 1) {
            imagedestroy($srcImg);
            copy($source, $dest);
            return;
        }

        $newW = (int)($srcW * $ratio);
        $newH = (int)($srcH * $ratio);

        $thumb = imagecreatetruecolor($newW, $newH);

        // Preserve transparency for PNG
        if ($info['mime'] === 'image/png') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }

        imagecopyresampled($thumb, $srcImg, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

        match ($info['mime']) {
            'image/jpeg' => imagejpeg($thumb, $dest, 85),
            'image/png' => imagepng($thumb, $dest, 8),
            'image/webp' => imagewebp($thumb, $dest, 85),
            default => null,
        };

        imagedestroy($srcImg);
        imagedestroy($thumb);
    }

    /**
     * Convert image to WebP format.
     */
    private static function convertToWebP(string $source, string $dest): void
    {
        $info = getimagesize($source);
        if (!$info) return;

        $srcImg = match ($info['mime']) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            default => null,
        };

        if (!$srcImg) return;

        imagewebp($srcImg, $dest, 85);
        imagedestroy($srcImg);
    }

    /**
     * Delete media and its files.
     */
    public static function delete(int $id): void
    {
        $db = Database::getInstance();
        $media = $db->fetchOne("SELECT * FROM zvele_media WHERE id = ?", [$id]);
        if (!$media) return;

        $absPath = ROOT_PATH . '/' . $media['path'];
        $dir = dirname($absPath);
        $base = pathinfo($media['filename'], PATHINFO_FILENAME);

        // Delete original, thumbnail, and webp
        foreach ([$absPath, $dir . '/thumb_' . $media['filename'], $dir . '/' . $base . '.webp'] as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $db->delete('zvele_media', 'id = ?', [$id]);
    }

    /**
     * Get thumbnail URL for a media item.
     */
    public static function thumbnailUrl(array $media): string
    {
        $dir = dirname($media['path']);
        $thumbPath = $dir . '/thumb_' . $media['filename'];
        if (file_exists(ROOT_PATH . '/' . $thumbPath)) {
            return url($thumbPath);
        }
        return url($media['path']);
    }
}
