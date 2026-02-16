<?php defined('ZVELE_CMS') or die(); ?>

<div x-data="formEditor(<?= e(json_encode($form['fields'])) ?>)">
    <form method="POST" action="<?= url('admin/forms/save' . ($form['id'] ? '/' . $form['id'] : '')) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="fields_json" :value="JSON.stringify(fields)">

        <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Název formuláře</label>
                        <input type="text" id="name" name="name" value="<?= e($form['name']) ?>" required
                               data-slug-source="slug" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>

                <!-- Field editor -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 class="font-semibold text-gray-900 mb-4">Pole formuláře</h2>
                    <div class="space-y-3">
                        <template x-for="(field, i) in fields" :key="i">
                            <div class="border border-gray-200 rounded-lg p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-400" x-text="'Pole ' + (i+1)"></span>
                                    <button type="button" @click="fields.splice(i, 1)" class="text-xs text-red-500">Odebrat</button>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" x-model="field.name" placeholder="Název (name)" class="px-3 py-1.5 border border-gray-300 rounded text-sm">
                                    <input type="text" x-model="field.label" placeholder="Popisek (label)" class="px-3 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <select x-model="field.type" class="px-3 py-1.5 border border-gray-300 rounded text-sm">
                                        <option value="text">Text</option>
                                        <option value="email">E-mail</option>
                                        <option value="tel">Telefon</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="select">Select</option>
                                    </select>
                                    <input type="text" x-model="field.placeholder" placeholder="Placeholder" class="px-3 py-1.5 border border-gray-300 rounded text-sm">
                                    <label class="flex items-center gap-1 text-xs text-gray-600">
                                        <input type="checkbox" x-model="field.required"> Povinné
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="fields.push({name:'',label:'',type:'text',placeholder:'',required:false})"
                            class="mt-3 text-sm text-blue-600 hover:text-blue-800">+ Přidat pole</button>
                </div>
            </div>

            <div class="w-full lg:w-80 space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                    <div>
                        <label for="email_to" class="block text-xs font-medium text-gray-500 mb-1">E-mail příjemce</label>
                        <input type="email" id="email_to" name="email_to" value="<?= e($form['email_to']) ?>" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label for="email_subject" class="block text-xs font-medium text-gray-500 mb-1">Předmět e-mailu</label>
                        <input type="text" id="email_subject" name="email_subject" value="<?= e($form['email_subject']) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label for="success_message" class="block text-xs font-medium text-gray-500 mb-1">Zpráva po odeslání</label>
                        <textarea id="success_message" name="success_message" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e($form['success_message']) ?></textarea>
                    </div>
                    <div>
                        <label for="honeypot_field" class="block text-xs font-medium text-gray-500 mb-1">Honeypot pole</label>
                        <input type="text" id="honeypot_field" name="honeypot_field" value="<?= e($form['honeypot_field']) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">Uložit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function formEditor(initialFields) {
    return {
        fields: initialFields || []
    };
}
</script>
