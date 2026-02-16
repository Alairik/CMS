<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class MenuController
{
    public function index(): void
    {
        $this->edit('main');
    }

    public function edit(?string $location = null): void
    {
        $location = $location ?: 'main';
        $db = Database::getInstance();

        if (is_post()) {
            $itemsJson = $_POST['items_json'] ?? '[]';
            $items = json_decode($itemsJson, true);
            if ($items === null) $items = [];

            $existing = $db->fetchOne("SELECT id FROM zvele_menus WHERE location = ?", [$location]);
            if ($existing) {
                $db->update('zvele_menus', ['items' => json_encode($items, JSON_UNESCAPED_UNICODE)], 'location = ?', [$location]);
            } else {
                $db->insert('zvele_menus', ['location' => $location, 'items' => json_encode($items, JSON_UNESCAPED_UNICODE)]);
            }

            flash('success', 'Menu bylo uloženo.');
            redirect(url('admin/menus/edit/' . $location));
        }

        $menu = $db->fetchOne("SELECT * FROM zvele_menus WHERE location = ?", [$location]);
        $menuItems = $menu ? (json_decode($menu['items'], true) ?? []) : [];

        $pages = $db->fetchAll("SELECT id, title, slug FROM zvele_pages WHERE status = 'published' ORDER BY title ASC");

        $pageTitle = 'Navigace — ' . ucfirst($location);
        $section = 'menus';

        ob_start();
        require ADMIN_PATH . '/views/menus/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }
}
