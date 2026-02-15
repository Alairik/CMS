<?php defined('ZVELE_CMS') or die(); ?>

<section class="section">
    <div class="container <?= ($width ?? 'narrow') === 'narrow' ? 'container--narrow' : '' ?>">
        <div class="prose">
            <?= $content ?? '' ?>
        </div>
    </div>
</section>
