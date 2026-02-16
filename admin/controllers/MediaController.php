<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

require_once CORE_PATH . '/Media.php';

class MediaController
{
    public function index(): void
    {
        $db = Database::getInstance();

        // Handle upload
        if (is_post() && !empty($_FILES['file'])) {
            try {
                $id = Media::upload($_FILES['file']);
                flash('success', 'Soubor byl nahrán.');
            } catch (\RuntimeException $e) {
                flash('error', $e->getMessage());
            }
            redirect(url('admin/media'));
        }

        // Handle alt text update
        if (is_post() && isset($_POST['update_alt'])) {
            $mediaId = (int)$_POST['media_id'];
            $altText = Security::sanitize($_POST['alt_text'] ?? '');
            $db->update('zvele_media', ['alt_text' => $altText], 'id = ?', [$mediaId]);
            flash('success', 'Alt text byl aktualizován.');
            redirect(url('admin/media'));
        }

        $media = $db->fetchAll("SELECT * FROM zvele_media ORDER BY created_at DESC");

        $pageTitle = 'Média';
        $section = 'media';

        ob_start();
        require ADMIN_PATH . '/views/media/index.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function delete(?string $id = null): void
    {
        if ($id) {
            Media::delete((int)$id);
            flash('success', 'Soubor byl smazán.');
        }
        redirect(url('admin/media'));
    }
}
