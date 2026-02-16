<?php defined('ZVELE_CMS') or die(); ?>

<?php require ADMIN_PATH . '/views/settings/_tabs.php'; ?>

<form method="POST" action="<?= url('admin/settings/seo') ?>">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 max-w-2xl">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta title separator</label>
            <input type="text" name="settings[meta_title_separator]" value="<?= e(setting('meta_title_separator', '|')) ?>"
                   class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta title pattern</label>
            <input type="text" name="settings[meta_title_pattern]" value="<?= e(setting('meta_title_pattern', '{page_title} {separator} {site_name}')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <p class="text-xs text-gray-400 mt-1">Proměnné: {page_title}, {separator}, {site_name}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Default OG image ID</label>
            <input type="number" name="settings[default_og_image_id]" value="<?= e(setting('default_og_image_id', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Název organizace (Schema.org)</label>
            <input type="text" name="settings[schema_organization_name]" value="<?= e(setting('schema_organization_name', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vlastní robots.txt</label>
            <textarea name="settings[robots_txt_custom]" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"><?= e(setting('robots_txt_custom', '')) ?></textarea>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg transition-colors">Uložit &amp; regenerovat sitemap</button>
    </div>
</form>
