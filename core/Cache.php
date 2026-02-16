<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Cache
{
    /**
     * Get cached page HTML if available.
     */
    public static function get(string $uri): ?string
    {
        if (ENVIRONMENT === 'dev') {
            return null;
        }

        $file = self::cacheFile($uri);
        if (!file_exists($file)) {
            return null;
        }

        // Cache expires after 1 hour
        if (filemtime($file) < time() - 3600) {
            unlink($file);
            return null;
        }

        return file_get_contents($file);
    }

    /**
     * Store page HTML in cache.
     */
    public static function put(string $uri, string $html): void
    {
        if (ENVIRONMENT === 'dev') {
            return;
        }

        $dir = CACHE_PATH . '/pages';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(self::cacheFile($uri), $html, LOCK_EX);
    }

    /**
     * Invalidate cache for specific URI.
     */
    public static function invalidate(string $uri): void
    {
        $file = self::cacheFile($uri);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Clear all cached pages.
     */
    public static function flush(): void
    {
        $dir = CACHE_PATH . '/pages';
        if (!is_dir($dir)) return;

        $files = glob($dir . '/*.html');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    /**
     * Clean expired rate limit files.
     */
    public static function cleanRateLimits(): void
    {
        $dir = CACHE_PATH . '/rate';
        if (!is_dir($dir)) return;

        $files = glob($dir . '/*.json');
        $cutoff = time() - 7200; // 2 hours

        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }

    private static function cacheFile(string $uri): string
    {
        return CACHE_PATH . '/pages/' . md5($uri) . '.html';
    }
}
