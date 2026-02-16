<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class PostController
{
    public function index(): void
    {
        $db = Database::getInstance();
        $posts = $db->fetchAll(
            "SELECT p.*, u.name as author_name
             FROM zvele_posts p
             LEFT JOIN zvele_users u ON p.author_id = u.id
             ORDER BY p.created_at DESC"
        );

        $pageTitle = 'Blog posty';
        $section = 'posts';

        ob_start();
        require ADMIN_PATH . '/views/posts/index.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function create(): void
    {
        $post = [
            'id' => null,
            'title' => '',
            'slug' => '',
            'excerpt' => '',
            'meta_title' => '',
            'meta_description' => '',
            'og_image_id' => null,
            'canonical_url' => '',
            'content' => '',
            'category' => '',
            'tags' => [],
            'status' => 'draft',
            'featured' => 0,
        ];

        $categories = $this->getCategories();

        $pageTitle = 'Nový post';
        $section = 'posts';

        ob_start();
        require ADMIN_PATH . '/views/posts/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function edit(?string $id = null): void
    {
        if (!$id) {
            redirect(url('admin/posts'));
        }

        $db = Database::getInstance();
        $post = $db->fetchOne("SELECT * FROM zvele_posts WHERE id = ?", [(int)$id]);

        if (!$post) {
            flash('error', 'Post nenalezen.');
            redirect(url('admin/posts'));
        }

        $post['tags'] = json_decode($post['tags'], true) ?? [];
        $categories = $this->getCategories();

        $pageTitle = 'Upravit: ' . $post['title'];
        $section = 'posts';

        ob_start();
        require ADMIN_PATH . '/views/posts/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function save(?string $id = null): void
    {
        $db = Database::getInstance();

        $contentRaw = $_POST['content'] ?? '';
        $wordCount = str_word_count(strip_tags($contentRaw));
        $readingTime = max(1, (int)ceil($wordCount / 200));

        $tags = $_POST['tags'] ?? '';
        if (is_string($tags)) {
            $tags = array_filter(array_map('trim', explode(',', $tags)));
        }

        $data = [
            'title' => Security::sanitize($_POST['title'] ?? ''),
            'slug' => slugify($_POST['slug'] ?? $_POST['title'] ?? ''),
            'excerpt' => Security::sanitize($_POST['excerpt'] ?? '') ?: null,
            'meta_title' => Security::sanitize($_POST['meta_title'] ?? '') ?: null,
            'meta_description' => Security::sanitize($_POST['meta_description'] ?? '') ?: null,
            'og_image_id' => !empty($_POST['og_image_id']) ? (int)$_POST['og_image_id'] : null,
            'canonical_url' => Security::sanitizeUrl($_POST['canonical_url'] ?? '') ?: null,
            'content' => $contentRaw,
            'category' => Security::sanitize($_POST['category'] ?? '') ?: null,
            'tags' => json_encode($tags, JSON_UNESCAPED_UNICODE),
            'status' => in_array($_POST['status'] ?? '', ['draft', 'published']) ? $_POST['status'] : 'draft',
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'word_count' => $wordCount,
            'reading_time_min' => $readingTime,
            'author_id' => Auth::id(),
        ];

        if (empty($data['title'])) {
            flash('error', 'Název postu je povinný.');
            redirect(url('admin/posts/' . ($id ? 'edit/' . $id : 'create')));
        }

        // Unique slug
        $existingSlug = $db->fetchOne(
            "SELECT id FROM zvele_posts WHERE slug = ? AND id != ?",
            [$data['slug'], (int)($id ?? 0)]
        );
        if ($existingSlug) {
            $data['slug'] .= '-' . time();
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        if ($id) {
            $existing = $db->fetchOne("SELECT published_at, status FROM zvele_posts WHERE id = ?", [(int)$id]);
            if ($existing && $existing['published_at'] && $data['status'] === 'published') {
                unset($data['published_at']);
            }
            unset($data['author_id']);

            $db->update('zvele_posts', $data, 'id = ?', [(int)$id]);
            flash('success', 'Post byl uložen.');
            redirect(url('admin/posts/edit/' . $id));
        } else {
            $newId = $db->insert('zvele_posts', $data);
            flash('success', 'Post byl vytvořen.');
            redirect(url('admin/posts/edit/' . $newId));
        }
    }

    public function delete(?string $id = null): void
    {
        if (!$id) {
            redirect(url('admin/posts'));
        }

        $db = Database::getInstance();
        $db->delete('zvele_posts', 'id = ?', [(int)$id]);
        flash('success', 'Post byl smazán.');
        redirect(url('admin/posts'));
    }

    private function getCategories(): array
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT DISTINCT category FROM zvele_posts WHERE category IS NOT NULL AND category != '' ORDER BY category");
        return array_column($rows, 'category');
    }
}
