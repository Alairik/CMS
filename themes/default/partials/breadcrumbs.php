<?php defined('ZVELE_CMS') or die(); ?>

<?php if (!empty($breadcrumbs)): ?>
<nav class="breadcrumbs" aria-label="Navigace drobečků">
    <ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?= url('/') ?>" itemprop="item"><span itemprop="name">Úvod</span></a>
            <meta itemprop="position" content="1">
        </li>
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
        <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <?php if (!empty($crumb['url']) && $i < count($breadcrumbs) - 1): ?>
                <a href="<?= e($crumb['url']) ?>" itemprop="item"><span itemprop="name"><?= e($crumb['title']) ?></span></a>
            <?php else: ?>
                <span itemprop="name" aria-current="page"><?= e($crumb['title']) ?></span>
            <?php endif; ?>
            <meta itemprop="position" content="<?= $i + 2 ?>">
        </li>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
