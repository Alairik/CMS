<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class RedirectController
{
    public function index(): void
    {
        $db = Database::getInstance();

        if (is_post() && isset($_POST['action'])) {
            if ($_POST['action'] === 'add') {
                $from = trim($_POST['from_url'] ?? '');
                $to = trim($_POST['to_url'] ?? '');
                $code = in_array((int)($_POST['status_code'] ?? 301), [301, 302]) ? (int)$_POST['status_code'] : 301;

                if ($from && $to) {
                    $db->insert('zvele_redirects', [
                        'from_url' => $from,
                        'to_url' => $to,
                        'status_code' => $code,
                    ]);
                    flash('success', 'Přesměrování bylo přidáno.');
                }
            } elseif ($_POST['action'] === 'delete') {
                $db->delete('zvele_redirects', 'id = ?', [(int)$_POST['redirect_id']]);
                flash('success', 'Přesměrování bylo smazáno.');
            }
            redirect(url('admin/redirects'));
        }

        $redirects = $db->fetchAll("SELECT * FROM zvele_redirects ORDER BY id DESC");

        $pageTitle = 'Přesměrování';
        $section = 'redirects';

        ob_start();
        require ADMIN_PATH . '/views/settings/redirects.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }
}
