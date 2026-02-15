<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

// Error reporting based on environment
if (ENVIRONMENT === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', ROOT_PATH . '/error.log');
}

// Timezone
date_default_timezone_set('Europe/Prague');

// Load core files
require_once CORE_PATH . '/helpers.php';
require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Security.php';
require_once CORE_PATH . '/Auth.php';
require_once CORE_PATH . '/Router.php';

// Session configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', '1');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1');
}

session_start();

// Generate CSRF token
csrf_token();

// Send security headers
Security::sendHeaders();
