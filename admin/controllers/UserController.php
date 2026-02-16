<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class UserController
{
    public function index(): void
    {
        Auth::requireAdmin();

        $db = Database::getInstance();
        $users = $db->fetchAll("SELECT * FROM zvele_users ORDER BY created_at DESC");

        $pageTitle = 'Uživatelé';
        $section = 'users';

        ob_start();
        require ADMIN_PATH . '/views/users/index.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function create(): void
    {
        Auth::requireAdmin();

        $user = ['id' => null, 'email' => '', 'name' => '', 'role' => 'editor'];
        $pageTitle = 'Nový uživatel';
        $section = 'users';

        ob_start();
        require ADMIN_PATH . '/views/users/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function edit(?string $id = null): void
    {
        Auth::requireAdmin();
        if (!$id) redirect(url('admin/users'));

        $db = Database::getInstance();
        $user = $db->fetchOne("SELECT id, email, name, role, last_login, created_at FROM zvele_users WHERE id = ?", [(int)$id]);
        if (!$user) {
            flash('error', 'Uživatel nenalezen.');
            redirect(url('admin/users'));
        }

        $pageTitle = 'Upravit: ' . $user['name'];
        $section = 'users';

        ob_start();
        require ADMIN_PATH . '/views/users/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function save(?string $id = null): void
    {
        Auth::requireAdmin();
        $db = Database::getInstance();

        $data = [
            'email' => Security::sanitizeEmail($_POST['email'] ?? ''),
            'name' => Security::sanitize($_POST['name'] ?? ''),
            'role' => in_array($_POST['role'] ?? '', ['admin', 'editor']) ? $_POST['role'] : 'editor',
        ];

        if (empty($data['email']) || empty($data['name'])) {
            flash('error', 'E-mail a jméno jsou povinné.');
            redirect(url('admin/users/' . ($id ? 'edit/' . $id : 'create')));
        }

        $password = $_POST['password'] ?? '';

        if ($id) {
            if ($password) {
                if (strlen($password) < 8) {
                    flash('error', 'Heslo musí mít alespoň 8 znaků.');
                    redirect(url('admin/users/edit/' . $id));
                }
                $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $db->update('zvele_users', $data, 'id = ?', [(int)$id]);
            flash('success', 'Uživatel byl upraven.');
            redirect(url('admin/users/edit/' . $id));
        } else {
            if (strlen($password) < 8) {
                flash('error', 'Heslo musí mít alespoň 8 znaků.');
                redirect(url('admin/users/create'));
            }
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            $newId = $db->insert('zvele_users', $data);
            flash('success', 'Uživatel byl vytvořen.');
            redirect(url('admin/users/edit/' . $newId));
        }
    }

    public function delete(?string $id = null): void
    {
        Auth::requireAdmin();
        if (!$id || (int)$id === Auth::id()) {
            flash('error', 'Nemůžete smazat vlastní účet.');
            redirect(url('admin/users'));
        }

        $db = Database::getInstance();
        $db->delete('zvele_users', 'id = ?', [(int)$id]);
        flash('success', 'Uživatel byl smazán.');
        redirect(url('admin/users'));
    }
}
