<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class SettingsController
{
    public function index(): void
    {
        $this->general();
    }

    public function general(): void
    {
        if (is_post()) {
            $this->saveSettings($_POST['settings'] ?? [], 'general');
            flash('success', 'Nastavení bylo uloženo.');
            redirect(url('admin/settings/general'));
        }

        $pageTitle = 'Nastavení — Obecné';
        $section = 'settings';
        $tab = 'general';

        ob_start();
        require ADMIN_PATH . '/views/settings/general.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function seo(): void
    {
        if (is_post()) {
            $this->saveSettings($_POST['settings'] ?? [], 'seo');
            // Regenerate sitemap
            require_once CORE_PATH . '/Sitemap.php';
            Sitemap::regenerateAll();
            flash('success', 'SEO nastavení uloženo. Sitemap regenerován.');
            redirect(url('admin/settings/seo'));
        }

        $pageTitle = 'Nastavení — SEO';
        $section = 'settings';
        $tab = 'seo';

        ob_start();
        require ADMIN_PATH . '/views/settings/seo.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function tracking(): void
    {
        if (is_post()) {
            $this->saveSettings($_POST['settings'] ?? [], 'tracking');
            flash('success', 'Tracking nastavení uloženo.');
            redirect(url('admin/settings/tracking'));
        }

        $pageTitle = 'Nastavení — Tracking';
        $section = 'settings';
        $tab = 'tracking';

        ob_start();
        require ADMIN_PATH . '/views/settings/tracking.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function cookies(): void
    {
        if (is_post()) {
            $this->saveSettings($_POST['settings'] ?? [], 'cookies');
            flash('success', 'Cookie nastavení uloženo.');
            redirect(url('admin/settings/cookies'));
        }

        $pageTitle = 'Nastavení — Cookies';
        $section = 'settings';
        $tab = 'cookies';

        ob_start();
        require ADMIN_PATH . '/views/settings/cookies.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    private function saveSettings(array $settings, string $group): void
    {
        $db = Database::getInstance();
        foreach ($settings as $key => $value) {
            $existing = $db->fetchOne("SELECT `key` FROM zvele_settings WHERE `key` = ?", [$key]);
            if ($existing) {
                $db->update('zvele_settings', ['value' => $value], '`key` = ?', [$key]);
            } else {
                $db->insert('zvele_settings', [
                    'key' => $key,
                    'value' => $value,
                    'type' => 'string',
                    'group' => $group,
                ]);
            }
        }
    }
}
