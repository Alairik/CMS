<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Sitemap
{
    /**
     * Generate sitemap.xml content.
     */
    public static function generateXml(): string
    {
        $db = Database::getInstance();
        $siteUrl = setting('site_url', SITE_URL);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= self::urlEntry($siteUrl, date('c'), 'daily', '1.0');

        // Pages
        $pages = $db->fetchAll(
            "SELECT slug, updated_at FROM zvele_pages WHERE status = 'published' AND no_index = 0 ORDER BY sort_order ASC"
        );
        foreach ($pages as $page) {
            $loc = $siteUrl . '/' . $page['slug'];
            $xml .= self::urlEntry($loc, date('c', strtotime($page['updated_at'])), 'weekly', '0.8');
        }

        // Blog index
        $xml .= self::urlEntry($siteUrl . '/blog', date('c'), 'daily', '0.7');

        // Posts
        $posts = $db->fetchAll(
            "SELECT slug, updated_at FROM zvele_posts WHERE status = 'published' ORDER BY published_at DESC"
        );
        foreach ($posts as $post) {
            $loc = $siteUrl . '/blog/' . $post['slug'];
            $xml .= self::urlEntry($loc, date('c', strtotime($post['updated_at'])), 'monthly', '0.6');
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate robots.txt content.
     */
    public static function generateRobotsTxt(): string
    {
        $siteUrl = setting('site_url', SITE_URL);
        $custom = setting('robots_txt_custom', '');

        $txt = "User-agent: *\n";
        $txt .= "Allow: /\n";
        $txt .= "Disallow: /admin/\n";
        $txt .= "Disallow: /cache/\n";
        $txt .= "Disallow: /core/\n";
        $txt .= "\n";
        $txt .= "Sitemap: {$siteUrl}/content/sitemap.xml\n";

        if ($custom) {
            $txt .= "\n" . $custom . "\n";
        }

        return $txt;
    }

    /**
     * Generate llms.txt for AI crawlers.
     */
    public static function generateLlmsTxt(): string
    {
        $db = Database::getInstance();
        $siteUrl = setting('site_url', SITE_URL);
        $siteName = setting('site_name', 'ZveleCMS');
        $siteDescription = setting('site_description', '');

        $txt = "# {$siteName}\n\n";
        if ($siteDescription) {
            $txt .= "{$siteDescription}\n\n";
        }

        // Pages
        $pages = $db->fetchAll(
            "SELECT title, slug, meta_description FROM zvele_pages WHERE status = 'published' AND no_index = 0 ORDER BY sort_order ASC"
        );
        if ($pages) {
            $txt .= "## Stránky\n\n";
            foreach ($pages as $page) {
                $txt .= "- [{$page['title']}]({$siteUrl}/{$page['slug']})";
                if (!empty($page['meta_description'])) {
                    $txt .= ": {$page['meta_description']}";
                }
                $txt .= "\n";
            }
            $txt .= "\n";
        }

        // Posts
        $posts = $db->fetchAll(
            "SELECT title, slug, excerpt, published_at FROM zvele_posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 50"
        );
        if ($posts) {
            $txt .= "## Blog\n\n";
            foreach ($posts as $post) {
                $txt .= "- [{$post['title']}]({$siteUrl}/blog/{$post['slug']})";
                if (!empty($post['excerpt'])) {
                    $txt .= ": " . excerpt(strip_tags($post['excerpt']), 120);
                }
                $txt .= "\n";
            }
        }

        return $txt;
    }

    /**
     * Write all content files (sitemap.xml, robots.txt, llms.txt).
     */
    public static function regenerateAll(): void
    {
        $dir = CONTENT_PATH;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($dir . '/sitemap.xml', self::generateXml(), LOCK_EX);
        file_put_contents($dir . '/robots.txt', self::generateRobotsTxt(), LOCK_EX);

        if (setting('enable_llms_txt', true)) {
            file_put_contents($dir . '/llms.txt', self::generateLlmsTxt(), LOCK_EX);
        }
    }

    private static function urlEntry(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n"
            . "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n"
            . "    <lastmod>{$lastmod}</lastmod>\n"
            . "    <changefreq>{$changefreq}</changefreq>\n"
            . "    <priority>{$priority}</priority>\n"
            . "  </url>\n";
    }
}
