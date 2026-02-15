<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

/**
 * Escape HTML output — primary XSS prevention.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate full URL from path.
 */
function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return rtrim(SITE_URL, '/') . ($path ? '/' . $path : '');
}

/**
 * Generate asset URL with cache-busting.
 */
function asset(string $path): string
{
    $filePath = ROOT_PATH . '/' . ltrim($path, '/');
    $version = file_exists($filePath) ? filemtime($filePath) : '1';
    return url($path) . '?v=' . $version;
}

/**
 * Generate CSRF hidden input field.
 */
function csrf_field(): string
{
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

/**
 * Generate CSRF token (called in bootstrap).
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token from POST request.
 */
function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Generate slug from string (Czech diacritics safe).
 */
function slugify(string $text): string
{
    if (function_exists('transliterator_transliterate')) {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    } else {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower($text);
    }
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Redirect to URL and exit.
 */
function redirect(string $url, int $code = 302): never
{
    http_response_code($code);
    header('Location: ' . $url);
    exit;
}

/**
 * Get setting value from database.
 */
function setting(string $key, mixed $default = null): mixed
{
    static $cache = [];

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $db = Database::getInstance();
    $row = $db->fetchOne("SELECT `value`, `type` FROM zvele_settings WHERE `key` = ?", [$key]);

    if (!$row) {
        return $default;
    }

    $value = match ($row['type']) {
        'int' => (int) $row['value'],
        'bool' => (bool) $row['value'],
        'json' => json_decode($row['value'], true),
        default => $row['value'],
    };

    $cache[$key] = $value;
    return $value;
}

/**
 * Flash message — set or get.
 */
function flash(string $type = null, string $message = null): ?array
{
    if ($type !== null && $message !== null) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        return null;
    }

    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Debug dump and die.
 */
function dd(mixed ...$vars): never
{
    if (ENVIRONMENT !== 'dev') {
        die('Debug not available.');
    }

    echo '<pre style="background:#1e293b;color:#e2e8f0;padding:1rem;margin:1rem;border-radius:0.5rem;overflow:auto;">';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Format date in Czech locale.
 */
function format_date(?string $datetime, ?string $format = null): string
{
    if (!$datetime) {
        return '';
    }

    $format = $format ?? setting('date_format', 'j. n. Y');
    return date($format, strtotime($datetime));
}

/**
 * Truncate text to given length.
 */
function excerpt(string $text, int $length = 160): string
{
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

/**
 * Get current request URI (without query string).
 */
function request_uri(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    return '/' . trim($uri, '/');
}

/**
 * Check if current request is POST.
 */
function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Get client IP address.
 */
function client_ip(): string
{
    return $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['HTTP_X_REAL_IP']
        ?? $_SERVER['REMOTE_ADDR']
        ?? '0.0.0.0';
}
