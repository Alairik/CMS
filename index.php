<?php
/**
 * ZveleCMS — Front Controller
 * All requests are routed through this file.
 */

define('ZVELE_CMS', true);

// Load configuration
require_once __DIR__ . '/config.php';

// Check if install needed
if (!file_exists(ROOT_PATH . '/config.php') || (file_exists(ROOT_PATH . '/install.php') && !defined('DB_NAME'))) {
    header('Location: /install.php');
    exit;
}

// Bootstrap the application
require_once CORE_PATH . '/bootstrap.php';

// Define frontend routes
Router::get('/', function () {
    $db = Database::getInstance();
    $page = $db->fetchOne(
        "SELECT * FROM zvele_pages WHERE slug = ? AND status = 'published'",
        ['homepage']
    );

    if (!$page) {
        // Fallback: show first published page
        $page = $db->fetchOne(
            "SELECT * FROM zvele_pages WHERE status = 'published' ORDER BY sort_order ASC, id ASC LIMIT 1"
        );
    }

    if ($page) {
        $page['blocks'] = json_decode($page['blocks'], true) ?? [];
        require_once CORE_PATH . '/Template.php';
        Template::render('page', ['page' => $page]);
    } else {
        echo '<!DOCTYPE html><html lang="cs"><head><meta charset="UTF-8"><title>' . e(SITE_NAME) . '</title></head>';
        echo '<body><h1>Vítejte v ZveleCMS</h1><p>Zatím nejsou vytvořeny žádné stránky.</p>';
        echo '<p><a href="/admin">Přejít do administrace</a></p></body></html>';
    }
});

// Static pages by slug
Router::get('/{slug}', function (string $slug) {
    // Skip admin routes
    if ($slug === 'admin') {
        return;
    }

    $db = Database::getInstance();
    $page = $db->fetchOne(
        "SELECT * FROM zvele_pages WHERE slug = ? AND status = 'published'",
        [$slug]
    );

    if (!$page) {
        Router::notFound();
    }

    $page['blocks'] = json_decode($page['blocks'], true) ?? [];
    require_once CORE_PATH . '/Template.php';
    Template::render('page', ['page' => $page]);
});

// Blog listing
Router::get('/blog', function () {
    $db = Database::getInstance();
    $perPage = (int) setting('blog_posts_per_page', 10);
    $currentPage = max(1, (int) ($_GET['page'] ?? 1));
    $offset = ($currentPage - 1) * $perPage;

    $total = $db->count('zvele_posts', "status = 'published'");
    $posts = $db->fetchAll(
        "SELECT p.*, u.name as author_name FROM zvele_posts p
         LEFT JOIN zvele_users u ON p.author_id = u.id
         WHERE p.status = 'published'
         ORDER BY p.published_at DESC
         LIMIT ? OFFSET ?",
        [$perPage, $offset]
    );

    $totalPages = (int) ceil($total / $perPage);

    require_once CORE_PATH . '/Template.php';
    Template::render('blog', [
        'posts' => $posts,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
    ]);
});

// Single blog post
Router::get('/blog/{slug}', function (string $slug) {
    $db = Database::getInstance();
    $post = $db->fetchOne(
        "SELECT p.*, u.name as author_name, u.email as author_email
         FROM zvele_posts p
         LEFT JOIN zvele_users u ON p.author_id = u.id
         WHERE p.slug = ? AND p.status = 'published'",
        [$slug]
    );

    if (!$post) {
        Router::notFound();
    }

    $post['tags'] = json_decode($post['tags'], true) ?? [];
    require_once CORE_PATH . '/Template.php';
    Template::render('post', ['post' => $post]);
});

// Frontend form submission
Router::post('/form/submit', function () {
    Security::validateCsrf();
    require_once CORE_PATH . '/Form.php';
    Form::handleSubmission();
});

// Dispatch the request
Router::dispatch();
