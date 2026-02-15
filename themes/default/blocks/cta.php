<?php defined('ZVELE_CMS') or die(); ?>

<?php $ctaStyle = $style ?? 'primary'; ?>
<section class="cta <?= $ctaStyle === 'secondary' ? 'cta--secondary' : 'cta--primary' ?>">
    <div class="container container--narrow text-center">
        <?php if (!empty($title)): ?>
            <h2 class="cta__title"><?= e($title) ?></h2>
        <?php endif; ?>
        <?php if (!empty($text)): ?>
            <p class="cta__text"><?= e($text) ?></p>
        <?php endif; ?>
        <?php if (!empty($button_text) && !empty($button_url)): ?>
            <a href="<?= e($button_url) ?>" class="btn btn--<?= e($ctaStyle) ?>"><?= e($button_text) ?></a>
        <?php endif; ?>
    </div>
</section>
