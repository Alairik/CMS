<?php defined('ZVELE_CMS') or die(); ?>
<div class="flex gap-1 mb-6 border-b border-gray-200 pb-px">
    <?php
    $tabs = [
        'general' => 'Obecné',
        'seo' => 'SEO',
        'tracking' => 'Tracking',
        'cookies' => 'Cookies',
    ];
    foreach ($tabs as $key => $label):
        $isActive = ($tab ?? 'general') === $key;
    ?>
    <a href="<?= url('admin/settings/' . $key) ?>"
       class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors <?= $isActive ? 'bg-white border border-gray-200 border-b-white text-blue-600 -mb-px' : 'text-gray-500 hover:text-gray-700' ?>">
        <?= e($label) ?>
    </a>
    <?php endforeach; ?>
</div>
