<?php defined('ZVELE_CMS') or die(); ?>

<?php
$videoUrl = $url ?? '';
$embedUrl = '';

// YouTube
if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
    $embedUrl = 'https://www.youtube-nocookie.com/embed/' . $m[1];
}
// Vimeo
elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
    $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
}

if (!$embedUrl) return;

$ratio = $aspect_ratio ?? '16:9';
$paddingClass = $ratio === '4:3' ? 'video-wrapper--4-3' : 'video-wrapper--16-9';
?>

<section class="section">
    <div class="container container--narrow">
        <?php if (!empty($title)): ?>
            <h2 class="section__title"><?= e($title) ?></h2>
        <?php endif; ?>

        <div class="video-wrapper <?= $paddingClass ?>">
            <iframe src="<?= e($embedUrl) ?>"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    loading="lazy"
                    title="<?= e($title ?? 'Video') ?>"></iframe>
        </div>
    </div>
</section>
