<?php defined('ZVELE_CMS') or die(); ?>
<!DOCTYPE html>
<html lang="<?= e(setting('language', 'cs')) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($seo['title'] ?? setting('site_name', 'ZveleCMS')) ?></title>

    <?php if (!empty($seo['meta_description'])): ?>
    <meta name="description" content="<?= e($seo['meta_description']) ?>">
    <?php endif; ?>

    <?php if (!empty($seo['no_index'])): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>

    <!-- Canonical -->
    <link rel="canonical" href="<?= e($seo['canonical'] ?? url(request_uri())) ?>">

    <!-- OG Tags -->
    <meta property="og:title" content="<?= e($seo['meta_title'] ?? $seo['title'] ?? '') ?>">
    <meta property="og:description" content="<?= e($seo['meta_description'] ?? '') ?>">
    <meta property="og:url" content="<?= e($seo['canonical'] ?? '') ?>">
    <meta property="og:type" content="<?= e($seo['og_type'] ?? 'website') ?>">
    <meta property="og:locale" content="cs_CZ">
    <meta property="og:site_name" content="<?= e(setting('site_name', '')) ?>">
    <?php if (!empty($seo['og_image'])): ?>
    <meta property="og:image" content="<?= e($seo['og_image']) ?>">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seo['meta_title'] ?? $seo['title'] ?? '') ?>">
    <meta name="twitter:description" content="<?= e($seo['meta_description'] ?? '') ?>">

    <!-- Preload CSS -->
    <link rel="preload" href="<?= asset('themes/default/assets/style.css') ?>" as="style">
    <link rel="stylesheet" href="<?= asset('themes/default/assets/style.css') ?>">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    <?php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => setting('site_name', ''),
        'url' => setting('site_url', SITE_URL),
    ];
    echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    ?>
    </script>

    <?php
    $orgName = setting('schema_organization_name', '');
    if ($orgName): ?>
    <script type="application/ld+json">
    <?php
    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $orgName,
        'url' => setting('site_url', SITE_URL),
    ];
    $orgLogoId = setting('schema_organization_logo_id');
    if ($orgLogoId) {
        $orgSchema['logo'] = Template::mediaUrl((int)$orgLogoId);
    }
    echo json_encode($orgSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    ?>
    </script>
    <?php endif; ?>
</head>
<body>
    <a href="#main" class="skip-link">Přeskočit na obsah</a>

    <?php Template::partial('header'); ?>

    <main id="main" role="main">
        <?= $pageContent ?>
    </main>

    <?php Template::partial('footer'); ?>

    <?php Template::partial('cookie-banner'); ?>

    <script src="<?= asset('themes/default/assets/app.js') ?>"></script>
</body>
</html>
