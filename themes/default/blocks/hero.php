<?php defined('ZVELE_CMS') or die(); ?>

<section class="hero" <?php if (!empty($image_id)): ?>style="background-image:url('<?= e(Template::mediaUrl((int)$image_id)) ?>')"<?php endif; ?>>
    <div class="hero__overlay" <?php if (isset($overlay_opacity)): ?>style="opacity:<?= ((int)$overlay_opacity) / 100 ?>"<?php endif; ?>></div>
    <div class="hero__content container">
        <?php if (!empty($title)): ?>
            <h1 class="hero__title"><?= e($title) ?></h1>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
            <p class="hero__subtitle"><?= e($subtitle) ?></p>
        <?php endif; ?>
        <?php if (!empty($cta_text) && !empty($cta_url)): ?>
            <a href="<?= e($cta_url) ?>" class="btn btn--primary hero__cta"><?= e($cta_text) ?></a>
        <?php endif; ?>
    </div>
</section>
