<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Router
{
    private static array $routes = [];
    private static ?string $matchedRoute = null;
    private static array $params = [];

    public static function add(string $method, string $pattern, callable|array $handler): void
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public static function get(string $pattern, callable|array $handler): void
    {
        self::add('GET', $pattern, $handler);
    }

    public static function post(string $pattern, callable|array $handler): void
    {
        self::add('POST', $pattern, $handler);
    }

    public static function any(string $pattern, callable|array $handler): void
    {
        self::add('GET', $pattern, $handler);
        self::add('POST', $pattern, $handler);
    }

    /**
     * Dispatch the current request.
     */
    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = request_uri();

        // Trailing slash redirect (except root)
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            redirect(rtrim($uri, '/'), 301);
        }

        // Check redirects from database
        self::checkRedirects($uri);

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = self::matchPattern($route['pattern'], $uri);
            if ($params !== false) {
                self::$matchedRoute = $route['pattern'];
                self::$params = is_array($params) ? $params : [];

                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    $controller = new $class();
                    $controller->$method(...self::$params);
                } else {
                    ($handler)(...self::$params);
                }
                return;
            }
        }

        self::notFound();
    }

    /**
     * Match URI against route pattern.
     * Supports {param} placeholders.
     */
    private static function matchPattern(string $pattern, string $uri): false|array
    {
        if ($pattern === $uri) {
            return [];
        }

        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return false;
    }

    /**
     * Check database for 301/302 redirects.
     */
    private static function checkRedirects(string $uri): void
    {
        try {
            $db = Database::getInstance();
            $redirect = $db->fetchOne(
                "SELECT to_url, status_code, id FROM zvele_redirects WHERE from_url = ?",
                [$uri]
            );

            if ($redirect) {
                $db->query(
                    "UPDATE zvele_redirects SET hits = hits + 1 WHERE id = ?",
                    [$redirect['id']]
                );
                redirect($redirect['to_url'], (int) $redirect['status_code']);
            }
        } catch (\PDOException) {
            // DB not available yet (before install), skip
        }
    }

    /**
     * Send 404 response.
     */
    public static function notFound(): never
    {
        http_response_code(404);

        $errorPage = THEME_PATH . '/layouts/404.php';
        if (file_exists($errorPage)) {
            require $errorPage;
        } else {
            echo '<!DOCTYPE html><html lang="cs"><head><meta charset="UTF-8"><title>404</title></head>';
            echo '<body><h1>404 — Stránka nenalezena</h1><p><a href="/">Zpět na hlavní stránku</a></p></body></html>';
        }
        exit;
    }

    public static function getParams(): array
    {
        return self::$params;
    }
}
