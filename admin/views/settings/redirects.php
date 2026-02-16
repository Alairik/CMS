<?php defined('ZVELE_CMS') or die(); ?>

<!-- Add redirect -->
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 max-w-3xl">
    <h2 class="font-semibold text-gray-900 text-sm mb-4">Přidat přesměrování</h2>
    <form method="POST" action="<?= url('admin/redirects') ?>" class="flex flex-wrap gap-2 items-end">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Z URL</label>
            <input type="text" name="from_url" placeholder="/stara-stranka" required
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-52">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Na URL</label>
            <input type="text" name="to_url" placeholder="/nova-stranka" required
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-52">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Kód</label>
            <select name="status_code" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="301">301 (trvalé)</option>
                <option value="302">302 (dočasné)</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">Přidat</button>
    </form>
</div>

<!-- List -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-3xl">
    <?php if (empty($redirects)): ?>
        <div class="px-6 py-8 text-center text-sm text-gray-500">Žádná přesměrování.</div>
    <?php else: ?>
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 border-b border-gray-200">
                <th class="text-left px-5 py-3 font-medium text-gray-500">Z</th>
                <th class="text-left px-5 py-3 font-medium text-gray-500">Na</th>
                <th class="text-left px-5 py-3 font-medium text-gray-500">Kód</th>
                <th class="text-left px-5 py-3 font-medium text-gray-500">Hity</th>
                <th class="text-right px-5 py-3 font-medium text-gray-500"></th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($redirects as $r): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3"><code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded"><?= e($r['from_url']) ?></code></td>
                    <td class="px-5 py-3"><code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded"><?= e($r['to_url']) ?></code></td>
                    <td class="px-5 py-3 text-gray-500"><?= (int)$r['status_code'] ?></td>
                    <td class="px-5 py-3 text-gray-500"><?= (int)$r['hits'] ?></td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="<?= url('admin/redirects') ?>" class="inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="redirect_id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" data-confirm="Smazat přesměrování?" class="text-gray-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
