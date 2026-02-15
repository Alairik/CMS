<?php defined('ZVELE_CMS') or die(); ?>

<?php
$db = Database::getInstance();
$footerMenu = $db->fetchOne("SELECT items FROM zvele_menus WHERE location = 'footer'");
$footerItems = $footerMenu ? (json_decode($footerMenu['items'], true) ?? []) : [];
$siteName = setting('site_name', 'ZveleCMS');
?>

<footer class="site-footer" role="contentinfo">
    <div class="container site-footer__inner">
        <div class="site-footer__info">
            <span class="site-footer__name"><?= e($siteName) ?></span>
            <span class="site-footer__copy">&copy; <?= date('Y') ?> <?= e($siteName) ?>. Všechna práva vyhrazena.</span>
        </div>

        <?php if (!empty($footerItems)): ?>
        <nav class="site-footer__nav" aria-label="Navigace v patičce">
            <ul class="site-footer__list">
                <?php foreach ($footerItems as $item): ?>
                <li>
                    <a href="<?= e($item['url'] ?? '#') ?>" class="site-footer__link">
                        <?= e($item['label'] ?? '') ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <?php endif; ?>

        <button type="button" class="site-footer__cookie-settings" data-cookie-settings>
            Upravit předvolby cookies
        </button>
    </div>
</footer>
