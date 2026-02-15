<?php defined('ZVELE_CMS') or die(); ?>

<section class="section">
    <div class="container">
        <?php if (!empty($title)): ?>
            <h2 class="section__title text-center"><?= e($title) ?></h2>
        <?php endif; ?>

        <?php if (!empty($items)): ?>
        <div class="features-grid">
            <?php foreach ($items as $item): ?>
            <div class="feature-card">
                <?php if (!empty($item['icon'])): ?>
                    <div class="feature-card__icon"><?= e($item['icon']) ?></div>
                <?php endif; ?>
                <?php if (!empty($item['title'])): ?>
                    <h3 class="feature-card__title"><?= e($item['title']) ?></h3>
                <?php endif; ?>
                <?php if (!empty($item['text'])): ?>
                    <p class="feature-card__text"><?= e($item['text']) ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
