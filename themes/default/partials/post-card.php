<?php defined('ZVELE_CMS') or die(); ?>

<?php
$showAuthor = setting('blog_show_author', true);
$showDate = setting('blog_show_date', true);
$showReadingTime = setting('blog_show_reading_time', true);
?>

<article class="post-card">
    <?php if (!empty($post['og_image_id'])): ?>
    <a href="<?= url('blog/' . $post['slug']) ?>" class="post-card__image">
        <img src="<?= e(Template::mediaUrl((int)$post['og_image_id'])) ?>"
             alt="<?= e($post['title']) ?>"
             loading="lazy" decoding="async">
    </a>
    <?php endif; ?>

    <div class="post-card__body">
        <?php if (!empty($post['category'])): ?>
            <span class="post-card__category"><?= e($post['category']) ?></span>
        <?php endif; ?>

        <h2 class="post-card__title">
            <a href="<?= url('blog/' . $post['slug']) ?>"><?= e($post['title']) ?></a>
        </h2>

        <?php if (!empty($post['excerpt'])): ?>
            <p class="post-card__excerpt"><?= e(excerpt($post['excerpt'], (int)setting('blog_excerpt_length', 160))) ?></p>
        <?php endif; ?>

        <div class="post-card__meta">
            <?php if ($showAuthor && !empty($post['author_name'])): ?>
                <span><?= e($post['author_name']) ?></span>
            <?php endif; ?>
            <?php if ($showDate && !empty($post['published_at'])): ?>
                <time datetime="<?= e($post['published_at']) ?>"><?= e(format_date($post['published_at'])) ?></time>
            <?php endif; ?>
            <?php if ($showReadingTime && !empty($post['reading_time_min'])): ?>
                <span><?= (int)$post['reading_time_min'] ?> min</span>
            <?php endif; ?>
        </div>
    </div>
</article>
