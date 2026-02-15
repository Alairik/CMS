<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class PageController
{
    public function index(): void
    {
        $db = Database::getInstance();
        $pages = $db->fetchAll(
            "SELECT p.*, pp.title as parent_title
             FROM zvele_pages p
             LEFT JOIN zvele_pages pp ON p.parent_id = pp.id
             ORDER BY p.sort_order ASC, p.title ASC"
        );

        $pageTitle = 'Stránky';
        $section = 'pages';

        ob_start();
        require ADMIN_PATH . '/views/pages/index.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function create(): void
    {
        $db = Database::getInstance();
        $page = [
            'id' => null,
            'title' => '',
            'slug' => '',
            'meta_title' => '',
            'meta_description' => '',
            'og_image_id' => null,
            'canonical_url' => '',
            'no_index' => 0,
            'blocks' => [],
            'schema_type' => 'WebPage',
            'status' => 'draft',
            'sort_order' => 0,
            'parent_id' => null,
            'template' => 'page',
        ];

        $parents = $db->fetchAll(
            "SELECT id, title FROM zvele_pages ORDER BY title ASC"
        );

        $pageTitle = 'Nová stránka';
        $section = 'pages';

        ob_start();
        require ADMIN_PATH . '/views/pages/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function edit(?string $id = null): void
    {
        if (!$id) {
            redirect(url('admin/pages'));
        }

        $db = Database::getInstance();
        $page = $db->fetchOne("SELECT * FROM zvele_pages WHERE id = ?", [(int)$id]);

        if (!$page) {
            flash('error', 'Stránka nenalezena.');
            redirect(url('admin/pages'));
        }

        $page['blocks'] = json_decode($page['blocks'], true) ?? [];

        $parents = $db->fetchAll(
            "SELECT id, title FROM zvele_pages WHERE id != ? ORDER BY title ASC",
            [(int)$id]
        );

        $pageTitle = 'Upravit: ' . $page['title'];
        $section = 'pages';

        ob_start();
        require ADMIN_PATH . '/views/pages/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function save(?string $id = null): void
    {
        $db = Database::getInstance();

        $data = [
            'title' => Security::sanitize($_POST['title'] ?? ''),
            'slug' => slugify($_POST['slug'] ?? $_POST['title'] ?? ''),
            'meta_title' => Security::sanitize($_POST['meta_title'] ?? '') ?: null,
            'meta_description' => Security::sanitize($_POST['meta_description'] ?? '') ?: null,
            'og_image_id' => !empty($_POST['og_image_id']) ? (int)$_POST['og_image_id'] : null,
            'canonical_url' => Security::sanitizeUrl($_POST['canonical_url'] ?? '') ?: null,
            'no_index' => isset($_POST['no_index']) ? 1 : 0,
            'blocks' => $_POST['blocks_json'] ?? '[]',
            'schema_type' => Security::sanitize($_POST['schema_type'] ?? 'WebPage'),
            'status' => in_array($_POST['status'] ?? '', ['draft', 'published']) ? $_POST['status'] : 'draft',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'template' => Security::sanitize($_POST['template'] ?? 'page'),
        ];

        if (empty($data['title'])) {
            flash('error', 'Název stránky je povinný.');
            redirect(url('admin/pages/' . ($id ? 'edit/' . $id : 'create')));
        }

        // Ensure slug is unique
        $existingSlug = $db->fetchOne(
            "SELECT id FROM zvele_pages WHERE slug = ? AND id != ?",
            [$data['slug'], (int)($id ?? 0)]
        );
        if ($existingSlug) {
            $data['slug'] .= '-' . time();
        }

        // Handle published_at
        if ($data['status'] === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Validate blocks JSON
        $blocksDecoded = json_decode($data['blocks'], true);
        if ($blocksDecoded === null && $data['blocks'] !== '[]') {
            $data['blocks'] = '[]';
        }

        if ($id) {
            // Keep original published_at if already published
            $existing = $db->fetchOne("SELECT published_at, status FROM zvele_pages WHERE id = ?", [(int)$id]);
            if ($existing && $existing['published_at'] && $data['status'] === 'published') {
                unset($data['published_at']);
            }

            $db->update('zvele_pages', $data, 'id = ?', [(int)$id]);
            flash('success', 'Stránka byla uložena.');

            // Invalidate cache
            $this->invalidateCache($data['slug']);

            redirect(url('admin/pages/edit/' . $id));
        } else {
            $newId = $db->insert('zvele_pages', $data);
            flash('success', 'Stránka byla vytvořena.');
            redirect(url('admin/pages/edit/' . $newId));
        }
    }

    public function delete(?string $id = null): void
    {
        if (!$id) {
            redirect(url('admin/pages'));
        }

        $db = Database::getInstance();
        $page = $db->fetchOne("SELECT slug FROM zvele_pages WHERE id = ?", [(int)$id]);

        if ($page) {
            $db->delete('zvele_pages', 'id = ?', [(int)$id]);
            $this->invalidateCache($page['slug']);
            flash('success', 'Stránka byla smazána.');
        }

        redirect(url('admin/pages'));
    }

    private function invalidateCache(string $slug): void
    {
        $cacheFile = CACHE_PATH . '/pages/' . md5('/' . $slug) . '.html';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
        // Also invalidate homepage cache
        $homeCache = CACHE_PATH . '/pages/' . md5('/') . '.html';
        if (file_exists($homeCache)) {
            unlink($homeCache);
        }
    }
}
