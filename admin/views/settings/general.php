<?php defined('ZVELE_CMS') or die(); ?>

<?php require ADMIN_PATH . '/views/settings/_tabs.php'; ?>

<form method="POST" action="<?= url('admin/settings/general') ?>">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 max-w-2xl">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Název webu</label>
            <input type="text" name="settings[site_name]" value="<?= e(setting('site_name', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">URL webu</label>
            <input type="url" name="settings[site_url]" value="<?= e(setting('site_url', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Popis webu</label>
            <textarea name="settings[site_description]" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e(setting('site_description', '')) ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Admin e-mail</label>
            <input type="email" name="settings[admin_email]" value="<?= e(setting('admin_email', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID loga (média)</label>
            <input type="number" name="settings[site_logo_id]" value="<?= e(setting('site_logo_id', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Formát data</label>
            <input type="text" name="settings[date_format]" value="<?= e(setting('date_format', 'j. n. Y')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg transition-colors">Uložit</button>
    </div>
</form>
