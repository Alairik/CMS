<?php defined('ZVELE_CMS') or die(); ?>

<?php
$showAuthor = setting('blog_show_author', true);
$showDate = setting('blog_show_date', true);
$showReadingTime = setting('blog_show_reading_time', true);
?>

<article class="section">
    <div class="container container--narrow">
        <?php Template::partial('breadcrumbs', ['breadcrumbs' => [
            ['title' => 'Blog', 'url' => '/blog'],
            ['title' => $post['title'], 'url' => ''],
        ]]); ?>

        <header style="margin-bottom:var(--space-xl);">
            <?php if (!empty($post['category'])): ?>
                <span style="display:inline-block;font-size:0.875rem;font-weight:500;color:var(--color-primary);margin-bottom:var(--space-sm);">
                    <?= e($post['category']) ?>
                </span>
            <?php endif; ?>

            <h1 style="font-size:clamp(1.75rem,4vw,2.5rem);font-weight:800;line-height:1.2;margin-bottom:var(--space-md);">
                <?= e($post['title']) ?>
            </h1>

            <div style="display:flex;flex-wrap:wrap;gap:var(--space-md);font-size:0.875rem;color:var(--color-text-light);">
                <?php if ($showAuthor && !empty($post['author_name'])): ?>
                    <span><?= e($post['author_name']) ?></span>
                <?php endif; ?>
                <?php if ($showDate && !empty($post['published_at'])): ?>
                    <time datetime="<?= e($post['published_at']) ?>"><?= e(format_date($post['published_at'])) ?></time>
                <?php endif; ?>
                <?php if ($showReadingTime && !empty($post['reading_time_min'])): ?>
                    <span><?= (int)$post['reading_time_min'] ?> min čtení</span>
                <?php endif; ?>
            </div>
        </header>

        <div class="prose">
            <?= $post['content'] ?>
        </div>

        <?php if (!empty($post['tags'])): ?>
        <footer style="margin-top:var(--space-xl);padding-top:var(--space-lg);border-top:1px solid var(--color-border);">
            <div style="display:flex;flex-wrap:wrap;gap:var(--space-sm);">
                <?php foreach ($post['tags'] as $tag): ?>
                    <span style="display:inline-block;padding:0.25rem 0.75rem;background:var(--color-surface);border-radius:9999px;font-size:0.8125rem;color:var(--color-text-light);">
                        <?= e($tag) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </footer>
        <?php endif; ?>
    </div>
</article>

<!-- Article JSON-LD -->
<script type="application/ld+json">
<?php
require_once CORE_PATH . '/SEO.php';
echo json_encode(SEO::articleSchema($post), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
</script>
