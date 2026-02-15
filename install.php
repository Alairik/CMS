<?php
/**
 * ZveleCMS — Installer
 * Creates database tables and first admin account.
 * DELETE THIS FILE AFTER INSTALLATION.
 */

define('ZVELE_CMS', true);

$errors = [];
$success = false;
$step = $_POST['step'] ?? 'form';

// Load config if exists
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

if ($step === 'install' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPassword = $_POST['admin_password'] ?? '';
    $adminName = trim($_POST['admin_name'] ?? 'Admin');

    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Zadejte platnou e-mailovou adresu.';
    }
    if (strlen($adminPassword) < 8) {
        $errors[] = 'Heslo musí mít alespoň 8 znaků.';
    }

    if (empty($errors)) {
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Create tables
            $sql = <<<'SQL'
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `zvele_settings` (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT,
    `type` ENUM('string','json','bool','int') DEFAULT 'string',
    `group` VARCHAR(50) DEFAULT 'general',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `meta_title` VARCHAR(70) DEFAULT NULL,
    `meta_description` VARCHAR(160) DEFAULT NULL,
    `og_image_id` INT UNSIGNED DEFAULT NULL,
    `canonical_url` VARCHAR(500) DEFAULT NULL,
    `no_index` TINYINT(1) DEFAULT 0,
    `blocks` JSON NOT NULL,
    `schema_type` VARCHAR(50) DEFAULT 'WebPage',
    `schema_data` JSON DEFAULT NULL,
    `status` ENUM('draft','published') DEFAULT 'draft',
    `sort_order` INT DEFAULT 0,
    `parent_id` INT UNSIGNED DEFAULT NULL,
    `template` VARCHAR(50) DEFAULT 'page',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `published_at` TIMESTAMP NULL,
    UNIQUE KEY `uq_slug` (`slug`),
    INDEX `idx_status` (`status`),
    INDEX `idx_parent` (`parent_id`),
    CONSTRAINT `fk_pages_parent` FOREIGN KEY (`parent_id`) REFERENCES `zvele_pages`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_posts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `excerpt` TEXT DEFAULT NULL,
    `meta_title` VARCHAR(70) DEFAULT NULL,
    `meta_description` VARCHAR(160) DEFAULT NULL,
    `og_image_id` INT UNSIGNED DEFAULT NULL,
    `canonical_url` VARCHAR(500) DEFAULT NULL,
    `content` LONGTEXT NOT NULL,
    `blocks` JSON DEFAULT NULL,
    `schema_data` JSON DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `tags` JSON DEFAULT NULL,
    `author_id` INT UNSIGNED NOT NULL,
    `status` ENUM('draft','published') DEFAULT 'draft',
    `featured` TINYINT(1) DEFAULT 0,
    `reading_time_min` SMALLINT UNSIGNED DEFAULT 0,
    `word_count` INT UNSIGNED DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `published_at` TIMESTAMP NULL,
    UNIQUE KEY `uq_slug` (`slug`),
    INDEX `idx_status_date` (`status`, `published_at`),
    INDEX `idx_category` (`category`),
    FULLTEXT `idx_search` (`title`, `excerpt`, `content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_media` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(500) NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `size` INT UNSIGNED NOT NULL,
    `width` SMALLINT UNSIGNED DEFAULT NULL,
    `height` SMALLINT UNSIGNED DEFAULT NULL,
    `alt_text` VARCHAR(255) NOT NULL DEFAULT '',
    `title` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_forms` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `fields` JSON NOT NULL,
    `email_to` VARCHAR(255) NOT NULL,
    `email_subject` VARCHAR(255) DEFAULT 'Nová zpráva z formuláře',
    `success_message` TEXT DEFAULT 'Děkujeme, zpráva byla odeslána.',
    `honeypot_field` VARCHAR(50) DEFAULT 'website_url',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_form_submissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `form_id` INT UNSIGNED NOT NULL,
    `data` JSON NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_submissions_form` FOREIGN KEY (`form_id`) REFERENCES `zvele_forms`(`id`) ON DELETE CASCADE,
    INDEX `idx_form` (`form_id`),
    INDEX `idx_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin','editor') DEFAULT 'editor',
    `last_login` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_menus` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `location` VARCHAR(50) NOT NULL,
    `items` JSON NOT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_redirects` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `from_url` VARCHAR(500) NOT NULL,
    `to_url` VARCHAR(500) NOT NULL,
    `status_code` SMALLINT UNSIGNED DEFAULT 301,
    `hits` INT UNSIGNED DEFAULT 0,
    INDEX `idx_from` (`from_url`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zvele_consent_log` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `visitor_hash` VARCHAR(64) NOT NULL,
    `consent_data` JSON NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_hash` (`visitor_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
SQL;

            // Execute multi-statement SQL
            $pdo->exec($sql);

            // Create admin user
            $passwordHash = password_hash($adminPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                "INSERT INTO zvele_users (email, password_hash, name, role) VALUES (?, ?, ?, 'admin')
                 ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), name = VALUES(name)"
            );
            $stmt->execute([$adminEmail, $passwordHash, $adminName]);

            // Insert default settings
            $defaults = [
                ['site_name', 'Můj web', 'string', 'general'],
                ['site_url', defined('SITE_URL') ? SITE_URL : 'https://example.cz', 'string', 'general'],
                ['site_description', '', 'string', 'general'],
                ['site_logo_id', '', 'string', 'general'],
                ['admin_email', $adminEmail, 'string', 'general'],
                ['language', 'cs', 'string', 'general'],
                ['timezone', 'Europe/Prague', 'string', 'general'],
                ['date_format', 'j. n. Y', 'string', 'general'],
                ['meta_title_separator', '|', 'string', 'seo'],
                ['meta_title_pattern', '{page_title} {separator} {site_name}', 'string', 'seo'],
                ['default_og_image_id', '', 'string', 'seo'],
                ['schema_organization_name', '', 'string', 'seo'],
                ['schema_organization_logo_id', '', 'string', 'seo'],
                ['schema_local_business', '{}', 'json', 'seo'],
                ['robots_txt_custom', '', 'string', 'seo'],
                ['enable_llms_txt', '1', 'bool', 'seo'],
                ['gtm_id', '', 'string', 'tracking'],
                ['ga4_id', '', 'string', 'tracking'],
                ['gads_id', '', 'string', 'tracking'],
                ['gads_conversion_label', '', 'string', 'tracking'],
                ['meta_pixel_id', '', 'string', 'tracking'],
                ['cookie_banner_title', 'Tato stránka používá cookies', 'string', 'cookies'],
                ['cookie_banner_text', 'Používáme cookies pro analýzu návštěvnosti a zlepšení funkčnosti webu.', 'string', 'cookies'],
                ['cookie_banner_accept', 'Přijmout vše', 'string', 'cookies'],
                ['cookie_banner_reject', 'Odmítnout vše', 'string', 'cookies'],
                ['cookie_banner_settings', 'Nastavit předvolby', 'string', 'cookies'],
                ['cookie_category_analytics_label', 'Analytické cookies', 'string', 'cookies'],
                ['cookie_category_analytics_text', 'Pomáhají nám pochopit, jak web používáte.', 'string', 'cookies'],
                ['cookie_category_marketing_label', 'Marketingové cookies', 'string', 'cookies'],
                ['cookie_category_marketing_text', 'Slouží k personalizaci reklam.', 'string', 'cookies'],
                ['blog_posts_per_page', '10', 'int', 'blog'],
                ['blog_excerpt_length', '160', 'int', 'blog'],
                ['blog_show_author', '1', 'bool', 'blog'],
                ['blog_show_date', '1', 'bool', 'blog'],
                ['blog_show_reading_time', '1', 'bool', 'blog'],
                ['form_rate_limit', '5', 'int', 'forms'],
                ['form_email_from', 'noreply@example.cz', 'string', 'forms'],
            ];

            $stmt = $pdo->prepare(
                "INSERT IGNORE INTO zvele_settings (`key`, `value`, `type`, `group`) VALUES (?, ?, ?, ?)"
            );

            foreach ($defaults as $row) {
                $stmt->execute($row);
            }

            // Create default menu locations
            $menuStmt = $pdo->prepare(
                "INSERT IGNORE INTO zvele_menus (location, items) VALUES (?, '[]')"
            );
            $menuStmt->execute(['main']);
            $menuStmt->execute(['footer']);

            // Create directories
            $dirs = [
                ROOT_PATH . '/uploads',
                ROOT_PATH . '/cache/pages',
                ROOT_PATH . '/cache/rate',
                ROOT_PATH . '/content',
            ];
            foreach ($dirs as $dir) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
            }

            $success = true;
        } catch (PDOException $ex) {
            $errors[] = 'Chyba databáze: ' . $ex->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalace ZveleCMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f1f5f9; color: #1e293b; line-height: 1.6; }
        .container { max-width: 500px; margin: 4rem auto; padding: 0 1rem; }
        .card { background: #fff; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        p.subtitle { color: #64748b; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-weight: 500; margin-bottom: 0.25rem; font-size: 0.875rem; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;
            font-size: 1rem; transition: border-color 0.15s;
        }
        input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        button {
            width: 100%; padding: 0.625rem; background: #2563eb; color: #fff; border: none;
            border-radius: 0.375rem; font-size: 1rem; font-weight: 500; cursor: pointer;
            transition: background 0.15s;
        }
        button:hover { background: #1d4ed8; }
        .error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem; font-size: 0.875rem; }
        .success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 1rem; border-radius: 0.375rem; text-align: center; }
        .success a { color: #2563eb; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>ZveleCMS Instalace</h1>

            <?php if ($success): ?>
                <div class="success">
                    <p><strong>Instalace proběhla úspěšně!</strong></p>
                    <p style="margin-top:0.5rem;">Smažte soubor <code>install.php</code> a poté se přihlaste.</p>
                    <p style="margin-top:0.75rem;"><a href="/admin">Přejít do administrace &rarr;</a></p>
                </div>
            <?php else: ?>
                <p class="subtitle">Vytvořte admin účet pro správu webu.</p>

                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $err): ?>
                            <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="step" value="install">

                    <div class="form-group">
                        <label for="admin_name">Jméno</label>
                        <input type="text" id="admin_name" name="admin_name" value="<?= htmlspecialchars($_POST['admin_name'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="admin_email">E-mail</label>
                        <input type="email" id="admin_email" name="admin_email" value="<?= htmlspecialchars($_POST['admin_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="admin_password">Heslo (min. 8 znaků)</label>
                        <input type="password" id="admin_password" name="admin_password" minlength="8" required>
                    </div>

                    <button type="submit">Nainstalovat ZveleCMS</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
