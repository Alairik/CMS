<article class="article-full fade-in-up">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>/">Domů</a>
        <?php if ($article['category_name']): ?>
            <span class="breadcrumb-sep">&rsaquo;</span>
            <a href="<?= SITE_URL ?>/kategorie/<?= h($article['category_slug'] ?? '') ?>"><?= h($article['category_name']) ?></a>
        <?php endif; ?>
        <span class="breadcrumb-sep">&rsaquo;</span>
        <span class="breadcrumb-current"><?= h($article['title']) ?></span>
    </nav>

    <?php if ($article['category_name']): ?>
    <a href="<?= SITE_URL ?>/kategorie/<?= h($article['category_slug'] ?? '') ?>" class="category-label"><?= h($article['category_name']) ?></a>
    <?php endif; ?>

    <h1><?= h($article['title']) ?></h1>

    <div class="article-meta">
        <span><?= format_date($article['created_at']) ?></span>
        <span class="meta-divider"></span>
        <span><?= h($article['author_name']) ?></span>
    </div>

    <?php if (!empty($article['featured_image'])): ?>
        <img src="<?= UPLOADS_URL . '/' . h($article['featured_image']) ?>" alt="<?= h($article['title']) ?>" class="featured-img">
    <?php endif; ?>

    <div class="article-content">
        <?= $article['content'] ?>
    </div>

    <?php if (!empty($articleTags)): ?>
    <div class="article-tags">
        <span class="tags-label">Tagy:</span>
        <?php foreach ($articleTags as $t): ?>
            <a href="<?= SITE_URL ?>/tag/<?= h($t['slug']) ?>" class="tag-pill"><?= h($t['name']) ?></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</article>
