<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class DashboardController
{
    public function index(): void
    {
        $db = Database::getInstance();

        $stats = [
            'pages' => $db->count('zvele_pages'),
            'published_pages' => $db->count('zvele_pages', "status = 'published'"),
            'posts' => $db->count('zvele_posts'),
            'published_posts' => $db->count('zvele_posts', "status = 'published'"),
            'media' => $db->count('zvele_media'),
            'unread_submissions' => $db->count('zvele_form_submissions', 'is_read = 0'),
        ];

        $recentPosts = $db->fetchAll(
            "SELECT p.id, p.title, p.status, p.created_at, u.name as author_name
             FROM zvele_posts p
             LEFT JOIN zvele_users u ON p.author_id = u.id
             ORDER BY p.created_at DESC LIMIT 5"
        );

        $recentSubmissions = $db->fetchAll(
            "SELECT s.id, s.created_at, s.is_read, f.name as form_name
             FROM zvele_form_submissions s
             LEFT JOIN zvele_forms f ON s.form_id = f.id
             ORDER BY s.created_at DESC LIMIT 5"
        );

        $pageTitle = 'Nástěnka';
        $section = 'dashboard';

        ob_start();
        require ADMIN_PATH . '/views/dashboard.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }
}
