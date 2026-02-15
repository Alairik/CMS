<?php defined('ZVELE_CMS') or die(); ?>

<?php
if (empty($form_id)) return;

$db = Database::getInstance();
$form = $db->fetchOne("SELECT * FROM zvele_forms WHERE id = ?", [(int)$form_id]);
if (!$form) return;

$fields = json_decode($form['fields'], true) ?? [];
?>

<section class="section" id="form-<?= (int)$form_id ?>">
    <div class="container container--narrow">
        <form method="POST" action="<?= url('form/submit') ?>" class="contact-form" data-form-id="<?= (int)$form_id ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="form_id" value="<?= (int)$form_id ?>">

            <!-- Honeypot -->
            <div style="position:absolute;left:-9999px" aria-hidden="true">
                <label for="<?= e($form['honeypot_field'] ?? 'website_url') ?>"><?= e($form['honeypot_field'] ?? 'website_url') ?></label>
                <input type="text" id="<?= e($form['honeypot_field'] ?? 'website_url') ?>"
                       name="<?= e($form['honeypot_field'] ?? 'website_url') ?>" tabindex="-1" autocomplete="off">
            </div>

            <?php foreach ($fields as $field): ?>
            <div class="form-group">
                <label for="field_<?= e($field['name'] ?? '') ?>"><?= e($field['label'] ?? '') ?>
                    <?php if (!empty($field['required'])): ?><span class="form-required" aria-hidden="true">*</span><?php endif; ?>
                </label>

                <?php $type = $field['type'] ?? 'text'; ?>

                <?php if ($type === 'textarea'): ?>
                    <textarea id="field_<?= e($field['name'] ?? '') ?>"
                              name="fields[<?= e($field['name'] ?? '') ?>]"
                              rows="5"
                              <?= !empty($field['required']) ? 'required' : '' ?>
                              <?= !empty($field['placeholder']) ? 'placeholder="' . e($field['placeholder']) . '"' : '' ?>></textarea>
                <?php elseif ($type === 'select'): ?>
                    <select id="field_<?= e($field['name'] ?? '') ?>"
                            name="fields[<?= e($field['name'] ?? '') ?>]"
                            <?= !empty($field['required']) ? 'required' : '' ?>>
                        <option value="">— Vyberte —</option>
                        <?php foreach (($field['options'] ?? []) as $opt): ?>
                            <option value="<?= e($opt) ?>"><?= e($opt) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="<?= e($type) ?>"
                           id="field_<?= e($field['name'] ?? '') ?>"
                           name="fields[<?= e($field['name'] ?? '') ?>]"
                           <?= !empty($field['required']) ? 'required' : '' ?>
                           <?= !empty($field['placeholder']) ? 'placeholder="' . e($field['placeholder']) . '"' : '' ?>>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn--primary">Odeslat</button>
        </form>
    </div>
</section>
