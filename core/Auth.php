<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Auth
{
    /**
     * Attempt login with email and password.
     */
    public static function attempt(string $email, string $password): bool
    {
        if (!Security::checkRateLimit('login_' . client_ip(), 10, 3600)) {
            return false;
        }

        $db = Database::getInstance();
        $user = $db->fetchOne(
            "SELECT id, email, password_hash, name, role FROM zvele_users WHERE email = ?",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Security::recordRateLimit('login_' . client_ip());
            return false;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        $db->update('zvele_users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);

        return true;
    }

    /**
     * Check if user is logged in.
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Require authentication — redirect to login if not authenticated.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('error', 'Pro přístup se musíte přihlásit.');
            redirect(url('admin/login'));
        }
    }

    /**
     * Require admin role.
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (self::role() !== 'admin') {
            flash('error', 'Nemáte oprávnění pro tuto akci.');
            redirect(url('admin'));
        }
    }

    /**
     * Logout current user.
     */
    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Get current user ID.
     */
    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user name.
     */
    public static function name(): ?string
    {
        return $_SESSION['user_name'] ?? null;
    }

    /**
     * Get current user role.
     */
    public static function role(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Get current user email.
     */
    public static function email(): ?string
    {
        return $_SESSION['user_email'] ?? null;
    }
}
