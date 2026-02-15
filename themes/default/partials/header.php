<?php defined('ZVELE_CMS') or die(); ?>

<?php
$db = Database::getInstance();
$menu = $db->fetchOne("SELECT items FROM zvele_menus WHERE location = 'main'");
$menuItems = $menu ? (json_decode($menu['items'], true) ?? []) : [];
$siteName = setting('site_name', 'ZveleCMS');
$logoId = setting('site_logo_id');
?>

<header class="site-header" role="banner">
    <div class="container site-header__inner">
        <a href="<?= url('/') ?>" class="site-header__logo" aria-label="<?= e($siteName) ?> — Přejít na hlavní stránku">
            <?php if ($logoId): ?>
                <img src="<?= e(Template::mediaUrl((int)$logoId)) ?>" alt="<?= e($siteName) ?>">
            <?php else: ?>
                <span class="site-header__name"><?= e($siteName) ?></span>
            <?php endif; ?>
        </a>

        <button class="site-header__toggle" aria-label="Otevřít menu" aria-expanded="false" data-menu-toggle>
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" role="navigation" aria-label="Hlavní navigace" data-menu>
            <?php if (!empty($menuItems)): ?>
            <ul class="site-nav__list">
                <?php foreach ($menuItems as $item): ?>
                <li class="site-nav__item">
                    <a href="<?= e($item['url'] ?? '#') ?>" class="site-nav__link"
                       <?= !empty($item['target']) ? 'target="' . e($item['target']) . '"' : '' ?>>
                        <?= e($item['label'] ?? '') ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </nav>
    </div>
</header>
