<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500"><?= count($forms) ?> formulářů</p>
    <a href="<?= url('admin/forms/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nový formulář
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (empty($forms)): ?>
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500">Zatím nemáte žádné formuláře.</p>
        </div>
    <?php else: ?>
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 border-b border-gray-200">
                <th class="text-left px-5 py-3 font-medium text-gray-500">Název</th>
                <th class="text-left px-5 py-3 font-medium text-gray-500 hidden md:table-cell">E-mail</th>
                <th class="text-left px-5 py-3 font-medium text-gray-500">Zprávy</th>
                <th class="text-right px-5 py-3 font-medium text-gray-500">Akce</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($forms as $f): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <a href="<?= url('admin/forms/edit/' . $f['id']) ?>" class="font-medium text-gray-900 hover:text-blue-600"><?= e($f['name']) ?></a>
                        <div class="text-xs text-gray-400">ID: <?= (int)$f['id'] ?></div>
                    </td>
                    <td class="px-5 py-3 text-gray-500 hidden md:table-cell"><?= e($f['email_to']) ?></td>
                    <td class="px-5 py-3">
                        <a href="<?= url('admin/forms/submissions/' . $f['id']) ?>" class="text-blue-600 hover:text-blue-800">
                            <?= (int)$f['submission_count'] ?>
                            <?php if ($f['unread_count'] > 0): ?>
                                <span class="ml-1 inline-flex px-1.5 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full"><?= (int)$f['unread_count'] ?> nových</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= url('admin/forms/edit/' . $f['id']) ?>" class="text-gray-400 hover:text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="<?= url('admin/forms/delete/' . $f['id']) ?>" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" data-confirm="Smazat formulář i všechny zprávy?" class="text-gray-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
