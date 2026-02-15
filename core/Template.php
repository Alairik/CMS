<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Template
{
    private static string $theme = 'default';

    /**
     * Render a layout with data.
     */
    public static function render(string $layout, array $data = []): void
    {
        extract($data);

        $layoutFile = ROOT_PATH . '/themes/' . self::$theme . '/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            $layoutFile = ROOT_PATH . '/themes/' . self::$theme . '/layouts/page.php';
        }

        // Prepare SEO data
        $seo = self::prepareSeo($layout, $data);

        // Render content into layout
        ob_start();
        require $layoutFile;
        $pageContent = ob_get_clean();

        // Wrap in base layout
        $baseFile = ROOT_PATH . '/themes/' . self::$theme . '/layouts/base.php';
        require $baseFile;
    }

    /**
     * Render a single block.
     */
    public static function renderBlock(array $block): string
    {
        $type = $block['type'] ?? '';
        $data = $block['data'] ?? [];

        $blockFile = ROOT_PATH . '/themes/' . self::$theme . '/blocks/' . $type . '.php';
        if (!file_exists($blockFile)) {
            return '';
        }

        ob_start();
        extract($data);
        require $blockFile;
        return ob_get_clean();
    }

    /**
     * Render all blocks for a page.
     */
    public static function renderBlocks(array $blocks): string
    {
        $output = '';
        foreach ($blocks as $block) {
            $output .= self::renderBlock($block);
        }
        return $output;
    }

    /**
     * Render a partial template.
     */
    public static function partial(string $name, array $data = []): void
    {
        $file = ROOT_PATH . '/themes/' . self::$theme . '/partials/' . $name . '.php';
        if (file_exists($file)) {
            extract($data);
            require $file;
        }
    }

    /**
     * Prepare SEO metadata.
     */
    private static function prepareSeo(string $layout, array $data): array
    {
        $siteName = setting('site_name', 'ZveleCMS');
        $siteUrl = setting('site_url', SITE_URL);
        $separator = setting('meta_title_separator', '|');
        $siteDescription = setting('site_description', '');

        $seo = [
            'title' => $siteName,
            'meta_title' => '',
            'meta_description' => $siteDescription,
            'canonical' => $siteUrl . request_uri(),
            'og_image' => '',
            'og_type' => 'website',
            'no_index' => false,
            'schema_type' => 'WebPage',
        ];

        if ($layout === 'page' && isset($data['page'])) {
            $page = $data['page'];
            $pageTitle = $page['meta_title'] ?? $page['title'];
            $seo['title'] = $pageTitle . ' ' . $separator . ' ' . $siteName;
            $seo['meta_title'] = $pageTitle;
            $seo['meta_description'] = $page['meta_description'] ?? $siteDescription;
            $seo['canonical'] = $page['canonical_url'] ?? ($siteUrl . '/' . $page['slug']);
            $seo['no_index'] = !empty($page['no_index']);
            $seo['schema_type'] = $page['schema_type'] ?? 'WebPage';

            if ($page['slug'] === 'homepage') {
                $seo['canonical'] = $siteUrl;
            }
        }

        if ($layout === 'post' && isset($data['post'])) {
            $post = $data['post'];
            $postTitle = $post['meta_title'] ?? $post['title'];
            $seo['title'] = $postTitle . ' ' . $separator . ' ' . $siteName;
            $seo['meta_title'] = $postTitle;
            $seo['meta_description'] = $post['meta_description'] ?? excerpt($post['excerpt'] ?? $post['content']);
            $seo['canonical'] = $post['canonical_url'] ?? ($siteUrl . '/blog/' . $post['slug']);
            $seo['og_type'] = 'article';
            $seo['schema_type'] = 'Article';
        }

        if ($layout === 'blog') {
            $seo['title'] = 'Blog ' . $separator . ' ' . $siteName;
            $seo['canonical'] = $siteUrl . '/blog';
        }

        return $seo;
    }

    /**
     * Get media URL by ID.
     */
    public static function mediaUrl(?int $id): string
    {
        if (!$id) {
            return '';
        }

        static $cache = [];
        if (isset($cache[$id])) {
            return $cache[$id];
        }

        $db = Database::getInstance();
        $media = $db->fetchOne("SELECT path FROM zvele_media WHERE id = ?", [$id]);

        $url = $media ? url($media['path']) : '';
        $cache[$id] = $url;
        return $url;
    }

    /**
     * Get media alt text by ID.
     */
    public static function mediaAlt(?int $id): string
    {
        if (!$id) {
            return '';
        }

        $db = Database::getInstance();
        $media = $db->fetchOne("SELECT alt_text FROM zvele_media WHERE id = ?", [$id]);
        return $media['alt_text'] ?? '';
    }
}
