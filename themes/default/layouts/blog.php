<?php defined('ZVELE_CMS') or die(); ?>

<section class="section">
    <div class="container">
        <h1 class="section__title">Blog</h1>

        <?php if (!empty($posts)): ?>
            <div class="post-grid">
                <?php foreach ($posts as $post): ?>
                    <?php Template::partial('post-card', ['post' => $post]); ?>
                <?php endforeach; ?>
            </div>

            <?php if (($totalPages ?? 1) > 1): ?>
                <?php Template::partial('pagination', [
                    'currentPage' => $currentPage ?? 1,
                    'totalPages' => $totalPages ?? 1,
                    'baseUrl' => url('blog'),
                ]); ?>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center" style="color:var(--color-text-light);padding:var(--space-3xl) 0;">
                Zatím nebyly publikovány žádné posty.
            </p>
        <?php endif; ?>
    </div>
</section>
