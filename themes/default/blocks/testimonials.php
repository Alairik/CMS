<?php defined('ZVELE_CMS') or die(); ?>

<section class="section section--surface">
    <div class="container">
        <?php if (!empty($title)): ?>
            <h2 class="section__title text-center"><?= e($title) ?></h2>
        <?php endif; ?>

        <?php if (!empty($items)): ?>
        <div class="testimonials-grid">
            <?php foreach ($items as $item): ?>
            <blockquote class="testimonial-card">
                <p class="testimonial-card__quote"><?= e($item['quote'] ?? '') ?></p>
                <footer class="testimonial-card__author">
                    <?php if (!empty($item['image_id'])): ?>
                    <img src="<?= e(Template::mediaUrl((int)$item['image_id'])) ?>"
                         alt="<?= e($item['author'] ?? '') ?>"
                         class="testimonial-card__avatar" loading="lazy" decoding="async">
                    <?php endif; ?>
                    <div>
                        <cite class="testimonial-card__name"><?= e($item['author'] ?? '') ?></cite>
                        <?php if (!empty($item['role'])): ?>
                            <span class="testimonial-card__role"><?= e($item['role']) ?></span>
                        <?php endif; ?>
                    </div>
                </footer>
            </blockquote>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
