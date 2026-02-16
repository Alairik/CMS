<?php defined('ZVELE_CMS') or die(); ?>

<?php require ADMIN_PATH . '/views/settings/_tabs.php'; ?>

<form method="POST" action="<?= url('admin/settings/cookies') ?>">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 max-w-2xl">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nadpis banneru</label>
            <input type="text" name="settings[cookie_banner_title]" value="<?= e(setting('cookie_banner_title', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Text banneru</label>
            <textarea name="settings[cookie_banner_text]" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e(setting('cookie_banner_text', '')) ?></textarea>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tlačítko Přijmout</label>
                <input type="text" name="settings[cookie_banner_accept]" value="<?= e(setting('cookie_banner_accept', 'Přijmout vše')) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tlačítko Odmítnout</label>
                <input type="text" name="settings[cookie_banner_reject]" value="<?= e(setting('cookie_banner_reject', 'Odmítnout vše')) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tlačítko Nastavení</label>
                <input type="text" name="settings[cookie_banner_settings]" value="<?= e(setting('cookie_banner_settings', 'Nastavit předvolby')) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <h3 class="font-semibold text-gray-900 text-sm pt-2">Analytické cookies</h3>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Název kategorie</label>
            <input type="text" name="settings[cookie_category_analytics_label]" value="<?= e(setting('cookie_category_analytics_label', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Popis</label>
            <textarea name="settings[cookie_category_analytics_text]" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e(setting('cookie_category_analytics_text', '')) ?></textarea>
        </div>
        <h3 class="font-semibold text-gray-900 text-sm pt-2">Marketingové cookies</h3>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Název kategorie</label>
            <input type="text" name="settings[cookie_category_marketing_label]" value="<?= e(setting('cookie_category_marketing_label', '')) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Popis</label>
            <textarea name="settings[cookie_category_marketing_text]" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e(setting('cookie_category_marketing_text', '')) ?></textarea>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg transition-colors">Uložit</button>
    </div>
</form>
