<?php defined('ZVELE_CMS') or die(); ?>

<?php require ADMIN_PATH . '/views/settings/_tabs.php'; ?>

<form method="POST" action="<?= url('admin/settings/tracking') ?>">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 max-w-2xl">
        <p class="text-sm text-gray-500">Tracking kódy se aktivují až po souhlasu uživatele s příslušnou kategorií cookies.</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Tag Manager ID</label>
            <input type="text" name="settings[gtm_id]" value="<?= e(setting('gtm_id', '')) ?>" placeholder="GTM-XXXXXXX"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics 4 ID</label>
            <input type="text" name="settings[ga4_id]" value="<?= e(setting('ga4_id', '')) ?>" placeholder="G-XXXXXXXXXX"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Ads ID</label>
            <input type="text" name="settings[gads_id]" value="<?= e(setting('gads_id', '')) ?>" placeholder="AW-XXXXXXXXXX"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Ads Conversion Label</label>
            <input type="text" name="settings[gads_conversion_label]" value="<?= e(setting('gads_conversion_label', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Pixel ID</label>
            <input type="text" name="settings[meta_pixel_id]" value="<?= e(setting('meta_pixel_id', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg transition-colors">Uložit</button>
    </div>
</form>
