<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500"><?= count($media) ?> souborů</p>
    <form method="POST" action="<?= url('admin/media') ?>" enctype="multipart/form-data" class="flex items-center gap-2">
        <?= csrf_field() ?>
        <input type="file" name="file" accept="image/*,.pdf" required
               class="text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Nahrát
        </button>
    </form>
</div>

<?php if (empty($media)): ?>
    <div class="bg-white rounded-xl border border-gray-200 px-6 py-12 text-center">
        <p class="text-gray-500">Zatím nemáte žádná média.</p>
    </div>
<?php else: ?>
    <div class="media-grid">
        <?php foreach ($media as $m): ?>
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
            <?php if (str_starts_with($m['mime_type'], 'image/')): ?>
                <div class="aspect-square bg-gray-100 cursor-pointer" @click="open = !open">
                    <img src="<?= e(Media::thumbnailUrl($m)) ?>" alt="<?= e($m['alt_text']) ?>"
                         class="w-full h-full object-cover" loading="lazy">
                </div>
            <?php else: ?>
                <div class="aspect-square bg-gray-100 flex items-center justify-center cursor-pointer" @click="open = !open">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            <?php endif; ?>

            <div class="p-3">
                <p class="text-xs text-gray-500 truncate" title="<?= e($m['original_name']) ?>"><?= e($m['original_name']) ?></p>
                <p class="text-xs text-gray-400"><?= e(number_format($m['size'] / 1024, 0)) ?> KB · ID: <?= (int)$m['id'] ?></p>
            </div>

            <!-- Detail panel -->
            <div x-show="open" x-transition class="border-t border-gray-100 p-3 space-y-2">
                <form method="POST" action="<?= url('admin/media') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="update_alt" value="1">
                    <input type="hidden" name="media_id" value="<?= (int)$m['id'] ?>">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Alt text</label>
                    <input type="text" name="alt_text" value="<?= e($m['alt_text']) ?>"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-xs mb-2">
                    <div class="flex gap-1">
                        <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Uložit</button>
                        <a href="<?= url('admin/media/delete/' . $m['id']) ?>"
                           data-confirm="Opravdu smazat?" class="text-xs text-red-600 hover:text-red-800 px-2 py-1">Smazat</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
