<?php defined('ZVELE_CMS') or die(); ?>

<form method="POST" action="<?= url('admin/posts/save' . ($post['id'] ? '/' . $post['id'] : '')) ?>">
    <?= csrf_field() ?>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main content -->
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Název postu</label>
                    <input type="text" id="title" name="title" value="<?= e($post['title']) ?>"
                           data-slug-source="slug" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">URL slug</label>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-400 mr-1"><?= e(SITE_URL) ?>/blog/</span>
                        <input type="text" id="slug" name="slug" value="<?= e($post['slug']) ?>"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Content editor -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Obsah</label>
                <textarea id="content" name="content" rows="20"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"><?= e($post['content']) ?></textarea>
            </div>

            <!-- Excerpt -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Výňatek</label>
                <textarea id="excerpt" name="excerpt" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                          placeholder="Krátký popis pro náhled postu..."><?= e($post['excerpt'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-80 space-y-6">
            <!-- Publish -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                <h3 class="font-semibold text-gray-900 text-sm">Publikování</h3>

                <div>
                    <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="draft" <?= ($post['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Koncept</option>
                        <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publikováno</option>
                    </select>
                </div>

                <div>
                    <label for="category" class="block text-xs font-medium text-gray-500 mb-1">Kategorie</label>
                    <input type="text" id="category" name="category" value="<?= e($post['category'] ?? '') ?>"
                           list="categories-list"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <datalist id="categories-list">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div>
                    <label for="tags" class="block text-xs font-medium text-gray-500 mb-1">Tagy (oddělené čárkou)</label>
                    <input type="text" id="tags" name="tags" value="<?= e(implode(', ', $post['tags'] ?? [])) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="featured" name="featured" value="1" <?= !empty($post['featured']) ? 'checked' : '' ?>
                           class="rounded border-gray-300">
                    <label for="featured" class="text-xs text-gray-600">Doporučený post</label>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                        Uložit
                    </button>
                    <a href="<?= url('admin/posts') ?>"
                       class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Zrušit
                    </a>
                </div>
            </div>

            <!-- SEO -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900 text-sm">SEO nastavení</h3>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="space-y-3">
                    <div>
                        <label for="meta_title" class="block text-xs font-medium text-gray-500 mb-1">Meta title</label>
                        <input type="text" id="meta_title" name="meta_title" value="<?= e($post['meta_title'] ?? '') ?>" maxlength="70"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-xs font-medium text-gray-500 mb-1">Meta description</label>
                        <textarea id="meta_description" name="meta_description" maxlength="160" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e($post['meta_description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label for="canonical_url" class="block text-xs font-medium text-gray-500 mb-1">Canonical URL</label>
                        <input type="url" id="canonical_url" name="canonical_url" value="<?= e($post['canonical_url'] ?? '') ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label for="og_image_id" class="block text-xs font-medium text-gray-500 mb-1">OG Image ID</label>
                        <input type="number" id="og_image_id" name="og_image_id" value="<?= e($post['og_image_id'] ?? '') ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
if (typeof tinymce !== 'undefined') {
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: false,
        plugins: 'lists link image code table',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.6; }',
        branding: false,
    });
}
</script>
