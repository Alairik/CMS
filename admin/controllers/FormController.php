<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class FormController
{
    public function index(): void
    {
        $db = Database::getInstance();
        $forms = $db->fetchAll(
            "SELECT f.*, (SELECT COUNT(*) FROM zvele_form_submissions WHERE form_id = f.id) as submission_count,
                    (SELECT COUNT(*) FROM zvele_form_submissions WHERE form_id = f.id AND is_read = 0) as unread_count
             FROM zvele_forms f ORDER BY f.created_at DESC"
        );

        $pageTitle = 'Formuláře';
        $section = 'forms';

        ob_start();
        require ADMIN_PATH . '/views/forms/index.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function create(): void
    {
        $form = [
            'id' => null, 'name' => '', 'slug' => '', 'fields' => [],
            'email_to' => '', 'email_subject' => 'Nová zpráva z formuláře',
            'success_message' => 'Děkujeme, zpráva byla odeslána.',
            'honeypot_field' => 'website_url',
        ];

        $pageTitle = 'Nový formulář';
        $section = 'forms';

        ob_start();
        require ADMIN_PATH . '/views/forms/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function edit(?string $id = null): void
    {
        if (!$id) redirect(url('admin/forms'));

        $db = Database::getInstance();
        $form = $db->fetchOne("SELECT * FROM zvele_forms WHERE id = ?", [(int)$id]);
        if (!$form) {
            flash('error', 'Formulář nenalezen.');
            redirect(url('admin/forms'));
        }
        $form['fields'] = json_decode($form['fields'], true) ?? [];

        $pageTitle = 'Upravit: ' . $form['name'];
        $section = 'forms';

        ob_start();
        require ADMIN_PATH . '/views/forms/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function save(?string $id = null): void
    {
        $db = Database::getInstance();

        $data = [
            'name' => Security::sanitize($_POST['name'] ?? ''),
            'slug' => slugify($_POST['slug'] ?? $_POST['name'] ?? ''),
            'fields' => $_POST['fields_json'] ?? '[]',
            'email_to' => Security::sanitizeEmail($_POST['email_to'] ?? ''),
            'email_subject' => Security::sanitize($_POST['email_subject'] ?? 'Nová zpráva z formuláře'),
            'success_message' => Security::sanitize($_POST['success_message'] ?? ''),
            'honeypot_field' => Security::sanitize($_POST['honeypot_field'] ?? 'website_url'),
        ];

        if (empty($data['name']) || empty($data['email_to'])) {
            flash('error', 'Název a e-mail příjemce jsou povinné.');
            redirect(url('admin/forms/' . ($id ? 'edit/' . $id : 'create')));
        }

        if ($id) {
            $db->update('zvele_forms', $data, 'id = ?', [(int)$id]);
            flash('success', 'Formulář byl uložen.');
            redirect(url('admin/forms/edit/' . $id));
        } else {
            $newId = $db->insert('zvele_forms', $data);
            flash('success', 'Formulář byl vytvořen.');
            redirect(url('admin/forms/edit/' . $newId));
        }
    }

    public function submissions(?string $id = null): void
    {
        if (!$id) redirect(url('admin/forms'));

        $db = Database::getInstance();
        $form = $db->fetchOne("SELECT * FROM zvele_forms WHERE id = ?", [(int)$id]);
        if (!$form) {
            flash('error', 'Formulář nenalezen.');
            redirect(url('admin/forms'));
        }

        // Mark as read
        if (is_post() && isset($_POST['mark_read'])) {
            $db->update('zvele_form_submissions', ['is_read' => 1], 'id = ?', [(int)$_POST['submission_id']]);
            redirect(url('admin/forms/submissions/' . $id));
        }

        $submissions = $db->fetchAll(
            "SELECT * FROM zvele_form_submissions WHERE form_id = ? ORDER BY created_at DESC",
            [(int)$id]
        );

        // Mark all as read
        $db->update('zvele_form_submissions', ['is_read' => 1], 'form_id = ? AND is_read = 0', [(int)$id]);

        $pageTitle = 'Zprávy: ' . $form['name'];
        $section = 'forms';

        ob_start();
        require ADMIN_PATH . '/views/forms/submissions.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function delete(?string $id = null): void
    {
        if ($id) {
            $db = Database::getInstance();
            $db->delete('zvele_forms', 'id = ?', [(int)$id]);
            flash('success', 'Formulář byl smazán.');
        }
        redirect(url('admin/forms'));
    }
}
