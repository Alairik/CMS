<?php if ($route === 'home' && (!isset($page) || $page === 1)): ?>

<!-- ============================================================
     HOMEPAGE — Showcase Layout
     ============================================================ -->

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-badge fade-in-up">Redakční systém</div>
        <h1 class="fade-in-up"><?= h(SITE_NAME) ?></h1>
        <p class="hero-subtitle fade-in-up">Moderní CMS pro tvorbu a správu webového obsahu. Elegantní, rychlý a plně přizpůsobitelný.</p>
        <div class="hero-stats fade-in-up">
            <div class="stat-item">
                <div class="stat-number"><?= $total ?></div>
                <div class="stat-label">Článků</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count($allCategories) ?></div>
                <div class="stat-label">Kategorií</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count($allTags) ?></div>
                <div class="stat-label">Tagů</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Article (first article, large card) -->
<?php if (!empty($articles)): ?>
<section class="section">
    <div class="container">
        <?php $featured = $articles[0]; ?>
        <article class="featured-article fade-in-up">
            <?php if (!empty($featured['featured_image'])): ?>
            <div class="featured-article-image-wrapper">
                <a href="<?= SITE_URL ?>/clanek/<?= h($featured['slug']) ?>">
                    <img src="<?= UPLOADS_URL . '/' . h($featured['featured_image']) ?>"
                         alt="<?= h($featured['title']) ?>" class="featured-article-image">
                </a>
            </div>
            <?php endif; ?>
            <div class="featured-article-body">
                <?php if ($featured['category_name']): ?>
                <a href="<?= SITE_URL ?>/kategorie/<?= h($featured['category_slug'] ?? '') ?>" class="category-label"><?= h($featured['category_name']) ?></a>
                <?php endif; ?>
                <h2><a href="<?= SITE_URL ?>/clanek/<?= h($featured['slug']) ?>"><?= h($featured['title']) ?></a></h2>
                <div class="article-meta">
                    <span><?= format_date($featured['created_at']) ?></span>
                    <span class="meta-divider"></span>
                    <span><?= h($featured['author_name']) ?></span>
                </div>
                <p class="article-excerpt"><?= h($featured['excerpt'] ?: excerpt($featured['content'], 250)) ?></p>
                <a href="<?= SITE_URL ?>/clanek/<?= h($featured['slug']) ?>" class="read-more">Číst článek &rarr;</a>
            </div>
        </article>
    </div>
</section>
<?php endif; ?>

<!-- Articles Grid (remaining articles) -->
<?php if (count($articles) > 1): ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Nejnovější články</h2>
            <p>Aktuální obsah z našeho redakčního systému</p>
            <span class="accent-line"></span>
        </div>
        <div class="articles-grid">
            <?php foreach (array_slice($articles, 1) as $a): ?>
            <article class="article-card fade-in-up">
                <?php if (!empty($a['featured_image'])): ?>
                <div class="image-wrapper">
                    <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>">
                        <img src="<?= UPLOADS_URL . '/' . h($a['featured_image']) ?>"
                             alt="<?= h($a['title']) ?>" class="article-card-image">
                    </a>
                </div>
                <?php endif; ?>
                <div class="article-card-body">
                    <?php if ($a['category_name']): ?>
                    <a href="<?= SITE_URL ?>/kategorie/<?= h($a['category_slug'] ?? '') ?>" class="category-label"><?= h($a['category_name']) ?></a>
                    <?php endif; ?>
                    <h2><a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>"><?= h($a['title']) ?></a></h2>
                    <div class="article-meta">
                        <span><?= format_date($a['created_at']) ?></span>
                        <span class="meta-divider"></span>
                        <span><?= h($a['author_name']) ?></span>
                    </div>
                    <p class="article-excerpt"><?= h($a['excerpt'] ?: excerpt($a['content'])) ?></p>
                    <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>" class="read-more">Číst dále &rarr;</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Categories Showcase -->
<?php if (!empty($allCategories)): ?>
<section class="section">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Kategorie</h2>
            <p>Prozkoumejte obsah podle témat</p>
            <span class="accent-line"></span>
        </div>
        <div class="categories-showcase">
            <?php foreach ($allCategories as $cat): ?>
            <a href="<?= SITE_URL ?>/kategorie/<?= h($cat['slug']) ?>" class="category-card fade-in-up">
                <div class="category-count"><?= $cat['article_count'] ?></div>
                <div class="category-name"><?= h($cat['name']) ?></div>
                <div class="category-desc">článků</div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Tag Cloud -->
<?php if (!empty($allTags)): ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Tagy</h2>
            <p>Klíčová slova napříč obsahem</p>
            <span class="accent-line"></span>
        </div>
        <div class="tag-cloud-showcase fade-in-up">
            <?php
            $maxCount = max(array_column($allTags, 'article_count') ?: [1]);
            foreach ($allTags as $t):
                $ratio = $maxCount > 0 ? $t['article_count'] / $maxCount : 0;
                $sizeClass = $ratio > 0.7 ? 'tag-pill--xl' : ($ratio > 0.4 ? 'tag-pill--lg' : '');
            ?>
            <a href="<?= SITE_URL ?>/tag/<?= h($t['slug']) ?>" class="tag-pill <?= $sizeClass ?>">
                <?= h($t['name']) ?>
                <span class="tag-count"><?= $t['article_count'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Homepage Pagination (if more than one page) -->
<?php if ($pag['total_pages'] > 1): ?>
<section class="section">
    <div class="container">
        <nav class="pagination">
            <?php if ($pag['current_page'] > 1): ?>
                <a href="<?= SITE_URL ?>/stranka/<?= $pag['current_page'] - 1 ?>" class="pagination-prev">&laquo; Předchozí</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
                <?php if ($i === $pag['current_page']): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/stranka/<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($pag['current_page'] < $pag['total_pages']): ?>
                <a href="<?= SITE_URL ?>/stranka/<?= $pag['current_page'] + 1 ?>" class="pagination-next">Další &raquo;</a>
            <?php endif; ?>
        </nav>
    </div>
</section>
<?php endif; ?>

<?php else: ?>

<!-- ============================================================
     CATEGORY / TAG / PAGINATED LISTING — Sidebar Layout
     ============================================================ -->

<div class="container">
    <div class="main-layout">
        <div class="main-content">
            <?php if (isset($category)): ?>
                <div class="page-title-bar fade-in-up">
                    <h1>Kategorie: <?= h($category['name']) ?></h1>
                </div>
            <?php elseif (isset($tag)): ?>
                <div class="page-title-bar fade-in-up">
                    <h1>Tag: <?= h($tag['name']) ?></h1>
                </div>
            <?php elseif ($route === 'home' && $page > 1): ?>
                <div class="page-title-bar fade-in-up">
                    <h1>Články — stránka <?= $page ?></h1>
                </div>
            <?php endif; ?>

            <?php if (empty($articles)): ?>
                <div class="empty-state fade-in-up">
                    <p>Zatím zde nejsou žádné články.</p>
                </div>
            <?php else: ?>
                <div class="articles-grid articles-grid--2col">
                    <?php foreach ($articles as $a): ?>
                    <article class="article-card fade-in-up">
                        <?php if (!empty($a['featured_image'])): ?>
                        <div class="image-wrapper">
                            <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>">
                                <img src="<?= UPLOADS_URL . '/' . h($a['featured_image']) ?>"
                                     alt="<?= h($a['title']) ?>" class="article-card-image">
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="article-card-body">
                            <?php if ($a['category_name']): ?>
                            <a href="<?= SITE_URL ?>/kategorie/<?= h($a['category_slug'] ?? '') ?>" class="category-label"><?= h($a['category_name']) ?></a>
                            <?php endif; ?>
                            <h2><a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>"><?= h($a['title']) ?></a></h2>
                            <div class="article-meta">
                                <span><?= format_date($a['created_at']) ?></span>
                                <span class="meta-divider"></span>
                                <span><?= h($a['author_name']) ?></span>
                            </div>
                            <p class="article-excerpt"><?= h($a['excerpt'] ?: excerpt($a['content'])) ?></p>
                            <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>" class="read-more">Číst dále &rarr;</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($pag['total_pages'] > 1): ?>
                <?php
                // Build pagination base URL
                if (isset($category)) {
                    $pagBaseUrl = SITE_URL . '/kategorie/' . h($category['slug']);
                } elseif (isset($tag)) {
                    $pagBaseUrl = SITE_URL . '/tag/' . h($tag['slug']);
                } else {
                    $pagBaseUrl = SITE_URL . '/stranka';
                }
                ?>
                <nav class="pagination">
                    <?php if ($pag['current_page'] > 1): ?>
                        <?php if (isset($category) || isset($tag)): ?>
                            <a href="<?= $pagBaseUrl ?>?page=<?= $pag['current_page'] - 1 ?>" class="pagination-prev">&laquo; Předchozí</a>
                        <?php else: ?>
                            <a href="<?= SITE_URL ?>/stranka/<?= $pag['current_page'] - 1 ?>" class="pagination-prev">&laquo; Předchozí</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
                        <?php if ($i === $pag['current_page']): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <?php if (isset($category) || isset($tag)): ?>
                                <a href="<?= $pagBaseUrl ?>?page=<?= $i ?>"><?= $i ?></a>
                            <?php else: ?>
                                <a href="<?= SITE_URL ?>/stranka/<?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($pag['current_page'] < $pag['total_pages']): ?>
                        <?php if (isset($category) || isset($tag)): ?>
                            <a href="<?= $pagBaseUrl ?>?page=<?= $pag['current_page'] + 1 ?>" class="pagination-next">Další &raquo;</a>
                        <?php else: ?>
                            <a href="<?= SITE_URL ?>/stranka/<?= $pag['current_page'] + 1 ?>" class="pagination-next">Další &raquo;</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <aside class="sidebar">
            <div class="widget">
                <h3>Kategorie</h3>
                <ul>
                    <?php foreach ($allCategories as $cat): ?>
                        <li>
                            <a href="<?= SITE_URL ?>/kategorie/<?= h($cat['slug']) ?>"><?= h($cat['name']) ?></a>
                            <span class="count-badge"><?= $cat['article_count'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (!empty($allTags)): ?>
            <div class="widget">
                <h3>Tagy</h3>
                <div class="tag-cloud">
                    <?php foreach ($allTags as $t): ?>
                        <a href="<?= SITE_URL ?>/tag/<?= h($t['slug']) ?>" class="tag-badge"><?= h($t['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</div>

<?php endif; ?>
