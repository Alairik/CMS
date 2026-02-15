<?php defined('ZVELE_CMS') or die(); ?>

<?php if (!empty($page['blocks'])): ?>
    <?= Template::renderBlocks($page['blocks']) ?>
<?php else: ?>
    <section class="section">
        <div class="container container--narrow">
            <h1><?= e($page['title'] ?? '') ?></h1>
        </div>
    </section>
<?php endif; ?>
