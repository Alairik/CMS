<?php
/**
 * ZveleCMS — Admin Front Controller
 * All admin requests are routed through this file.
 */

define('ZVELE_CMS', true);

require_once dirname(__DIR__) . '/config.php';
require_once CORE_PATH . '/bootstrap.php';

// Admin controller autoload
$controllerPath = ADMIN_PATH . '/controllers';

// Parse admin route
$uri = request_uri();
$uri = preg_replace('#^/admin/?#', '', $uri);
$uri = trim($uri, '/');

$segments = $uri ? explode('/', $uri) : ['dashboard'];
$section = $segments[0];
$action = $segments[1] ?? 'index';
$id = $segments[2] ?? null;

// Public routes (no auth required)
if ($section === 'login') {
    if (Auth::check()) {
        redirect(url('admin'));
    }

    if (is_post()) {
        Security::validateCsrf();
        $email = Security::sanitizeEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            flash('success', 'Přihlášení úspěšné.');
            redirect(url('admin'));
        } else {
            flash('error', 'Neplatný e-mail nebo heslo.');
        }
    }

    require ADMIN_PATH . '/views/login.php';
    exit;
}

if ($section === 'logout') {
    Auth::logout();
    flash('success', 'Byli jste odhlášeni.');
    redirect(url('admin/login'));
}

// All other routes require authentication
Auth::requireLogin();

// Route to appropriate controller
$controllerMap = [
    'dashboard' => 'DashboardController',
    'pages' => 'PageController',
    'posts' => 'PostController',
    'media' => 'MediaController',
    'forms' => 'FormController',
    'menus' => 'MenuController',
    'settings' => 'SettingsController',
    'redirects' => 'RedirectController',
    'users' => 'UserController',
];

if (!isset($controllerMap[$section])) {
    redirect(url('admin'));
}

$controllerClass = $controllerMap[$section];
$controllerFile = $controllerPath . '/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    flash('error', 'Sekce zatím není implementována.');
    redirect(url('admin'));
}

require_once $controllerFile;
$controller = new $controllerClass();

// CSRF validation on POST
if (is_post()) {
    Security::validateCsrf();
}

// Dispatch to controller method
if (method_exists($controller, $action)) {
    $controller->$action($id);
} else {
    $controller->index();
}
