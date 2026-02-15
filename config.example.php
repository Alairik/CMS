<?php
/**
 * ZveleCMS — Configuration
 * Rename this file to config.php and fill in your credentials.
 */

define('ZVELE_CMS', true);

// Environment: 'dev' or 'prod'
define('ENVIRONMENT', 'dev');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Site
define('SITE_URL', 'https://example.cz');
define('SITE_NAME', 'ZveleCMS');

// Paths
define('ROOT_PATH', __DIR__);
define('CORE_PATH', ROOT_PATH . '/core');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('THEME_PATH', ROOT_PATH . '/themes/default');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('CACHE_PATH', ROOT_PATH . '/cache');
define('CONTENT_PATH', ROOT_PATH . '/content');
