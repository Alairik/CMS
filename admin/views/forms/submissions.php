<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex items-center justify-between mb-6">
    <a href="<?= url('admin/forms') ?>" class="text-sm text-gray-500 hover:text-gray-700">&larr; Zpět na formuláře</a>
    <p class="text-sm text-gray-500"><?= count($submissions) ?> zpráv</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (empty($submissions)): ?>
        <div class="px-6 py-12 text-center"><p class="text-gray-500">Zatím žádné zprávy.</p></div>
    <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($submissions as $sub):
                $data = json_decode($sub['data'], true) ?? [];
            ?>
            <div class="p-5" x-data="{ open: false }">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div>
                        <span class="text-sm font-medium text-gray-900">
                            <?= e(array_values($data)[0] ?? 'Zpráva #' . $sub['id']) ?>
                        </span>
                        <span class="text-xs text-gray-400 ml-2"><?= e(format_date($sub['created_at'])) ?></span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div x-show="open" x-transition class="mt-3 space-y-1 text-sm">
                    <?php foreach ($data as $key => $value): ?>
                    <div class="flex">
                        <span class="w-32 text-gray-500 shrink-0"><?= e(ucfirst(str_replace('_', ' ', $key))) ?>:</span>
                        <span class="text-gray-900"><?= e($value) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <div class="flex pt-2 text-xs text-gray-400">
                        <span class="w-32">IP:</span><span><?= e($sub['ip_address'] ?? '') ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
