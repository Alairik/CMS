<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class SEO
{
    /**
     * Generate meta title from pattern.
     */
    public static function metaTitle(string $pageTitle): string
    {
        $siteName = setting('site_name', '');
        $separator = setting('meta_title_separator', '|');
        $pattern = setting('meta_title_pattern', '{page_title} {separator} {site_name}');

        return str_replace(
            ['{page_title}', '{separator}', '{site_name}'],
            [$pageTitle, $separator, $siteName],
            $pattern
        );
    }

    /**
     * Get meta description with fallback chain.
     */
    public static function metaDescription(?string $explicit, ?string $excerpt, ?string $content): string
    {
        if (!empty($explicit)) {
            return $explicit;
        }
        if (!empty($excerpt)) {
            return excerpt(strip_tags($excerpt), 160);
        }
        if (!empty($content)) {
            $text = strip_tags($content);
            // First sentence
            if (preg_match('/^(.+?[.!?])\s/u', $text, $m)) {
                return excerpt($m[1], 160);
            }
            return excerpt($text, 160);
        }
        return setting('site_description', '');
    }

    /**
     * Get OG image URL with fallback chain.
     */
    public static function ogImage(?int $explicitId, ?string $content = null): string
    {
        if ($explicitId) {
            $url = Template::mediaUrl($explicitId);
            if ($url) return $url;
        }

        // Extract first image from content
        if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $m)) {
            return $m[1];
        }

        // Global default
        $defaultId = setting('default_og_image_id');
        if ($defaultId) {
            return Template::mediaUrl((int)$defaultId);
        }

        return '';
    }

    /**
     * Generate BreadcrumbList JSON-LD.
     */
    public static function breadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        $siteUrl = setting('site_url', SITE_URL);

        $items[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Úvod',
            'item' => $siteUrl,
        ];

        foreach ($breadcrumbs as $i => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $i + 2,
                'name' => $crumb['title'],
                'item' => !empty($crumb['url']) ? $siteUrl . $crumb['url'] : null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Generate Article JSON-LD for blog posts.
     */
    public static function articleSchema(array $post): array
    {
        $siteUrl = setting('site_url', SITE_URL);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post['title'],
            'url' => $siteUrl . '/blog/' . $post['slug'],
            'datePublished' => $post['published_at'] ?? $post['created_at'],
            'dateModified' => $post['updated_at'] ?? $post['created_at'],
            'author' => [
                '@type' => 'Person',
                'name' => $post['author_name'] ?? 'Admin',
            ],
        ];

        if (!empty($post['meta_description']) || !empty($post['excerpt'])) {
            $schema['description'] = $post['meta_description'] ?? excerpt(strip_tags($post['excerpt'] ?? ''), 160);
        }

        $ogImage = self::ogImage($post['og_image_id'] ?? null, $post['content'] ?? null);
        if ($ogImage) {
            $schema['image'] = $ogImage;
        }

        if (!empty($post['word_count'])) {
            $schema['wordCount'] = (int)$post['word_count'];
        }

        return $schema;
    }

    /**
     * Generate LocalBusiness JSON-LD from settings.
     */
    public static function localBusinessSchema(): ?array
    {
        $data = setting('schema_local_business', '{}');
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (empty($data) || empty($data['name'])) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $data['name'] ?? '',
            'url' => setting('site_url', SITE_URL),
        ];

        if (!empty($data['address'])) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $data['address'] ?? '',
            ];
        }
        if (!empty($data['phone'])) {
            $schema['telephone'] = $data['phone'];
        }
        if (!empty($data['email'])) {
            $schema['email'] = $data['email'];
        }

        return $schema;
    }
}
