<?php defined('ZVELE_CMS') or die(); ?>

<?php $cols = (int)($columns ?? 3); ?>
<section class="section">
    <div class="container">
        <?php if (!empty($title)): ?>
            <h2 class="section__title"><?= e($title) ?></h2>
        <?php endif; ?>

        <?php if (!empty($images)): ?>
        <div class="gallery gallery--cols-<?= $cols ?>">
            <?php foreach ($images as $img): ?>
                <?php if (!empty($img['image_id'])): ?>
                <figure class="gallery__item">
                    <img src="<?= e(Template::mediaUrl((int)$img['image_id'])) ?>"
                         alt="<?= e($img['caption'] ?? Template::mediaAlt((int)$img['image_id'])) ?>"
                         loading="lazy" decoding="async">
                    <?php if (!empty($img['caption'])): ?>
                        <figcaption class="gallery__caption"><?= e($img['caption']) ?></figcaption>
                    <?php endif; ?>
                </figure>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
