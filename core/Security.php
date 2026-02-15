<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Security
{
    /**
     * Send security HTTP headers.
     */
    public static function sendHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-XSS-Protection: 1; mode=block');
    }

    /**
     * Check if action is within rate limit.
     * Returns true if allowed, false if rate limited.
     */
    public static function checkRateLimit(string $key, int $maxAttempts, int $windowSeconds): bool
    {
        $file = CACHE_PATH . '/rate/' . md5($key) . '.json';

        if (!file_exists($file)) {
            return true;
        }

        $data = json_decode(file_get_contents($file), true);
        if (!$data) {
            return true;
        }

        $cutoff = time() - $windowSeconds;
        $attempts = array_filter($data, fn($ts) => $ts > $cutoff);

        return count($attempts) < $maxAttempts;
    }

    /**
     * Record a rate limit hit.
     */
    public static function recordRateLimit(string $key): void
    {
        $dir = CACHE_PATH . '/rate';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir . '/' . md5($key) . '.json';
        $data = [];

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true) ?? [];
        }

        $data[] = time();

        // Keep only last 1 hour of entries
        $cutoff = time() - 3600;
        $data = array_values(array_filter($data, fn($ts) => $ts > $cutoff));

        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    /**
     * Validate CSRF token on POST requests.
     */
    public static function validateCsrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                http_response_code(403);
                die('Neplatný CSRF token. Obnovte stránku a zkuste to znovu.');
            }
        }
    }

    /**
     * Sanitize string input.
     */
    public static function sanitize(string $input): string
    {
        return trim(strip_tags($input));
    }

    /**
     * Sanitize email.
     */
    public static function sanitizeEmail(string $email): string
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Validate that input is a safe URL (no javascript: etc).
     */
    public static function sanitizeUrl(string $url): string
    {
        $url = trim($url);
        if (preg_match('/^(https?:\/\/|\/)/i', $url)) {
            return $url;
        }
        return '';
    }
}
