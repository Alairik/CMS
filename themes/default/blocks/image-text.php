<?php defined('ZVELE_CMS') or die(); ?>

<?php $position = $image_position ?? 'left'; ?>
<section class="section">
    <div class="container">
        <div class="image-text <?= $position === 'right' ? 'image-text--reverse' : '' ?>">
            <?php if (!empty($image_id)): ?>
            <div class="image-text__image">
                <img src="<?= e(Template::mediaUrl((int)$image_id)) ?>"
                     alt="<?= e(Template::mediaAlt((int)$image_id)) ?>"
                     loading="lazy" decoding="async">
            </div>
            <?php endif; ?>
            <div class="image-text__content prose">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>
</section>
