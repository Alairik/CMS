<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= h($metaDescription ?? '') ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= h($pageTitle ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= h($metaDescription ?? '') ?>">
    <meta property="og:type" content="<?= isset($article) && $template === 'article' ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= SITE_URL ?><?= h($_SERVER['REQUEST_URI']) ?>">
    <?php if (isset($article) && !empty($article['featured_image'])): ?>
    <meta property="og:image" content="<?= UPLOADS_URL . '/' . h($article['featured_image']) ?>">
    <?php endif; ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="<?= SITE_URL ?>/" class="site-logo"><?= h(SITE_NAME) ?></a>
        <button class="nav-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <nav class="site-nav">
            <a href="<?= SITE_URL ?>/" class="nav-link<?= $route === 'home' ? ' active' : '' ?>">Domů</a>
            <?php foreach ($allCategories as $cat): ?>
                <a href="<?= SITE_URL ?>/kategorie/<?= h($cat['slug']) ?>" class="nav-link<?= (isset($category) && $category['slug'] === $cat['slug']) ? ' active' : '' ?>"><?= h($cat['name']) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
