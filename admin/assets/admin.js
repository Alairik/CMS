/**
 * ZveleCMS Admin — Custom JavaScript
 * Extends Alpine.js CDN with custom functionality.
 */

document.addEventListener('DOMContentLoaded', function () {

    // Auto-generate slug from title
    document.querySelectorAll('[data-slug-source]').forEach(function (input) {
        var targetId = input.getAttribute('data-slug-source');
        var target = document.getElementById(targetId);
        if (!target) return;

        var manuallyEdited = target.value !== '';

        target.addEventListener('input', function () {
            manuallyEdited = true;
        });

        input.addEventListener('input', function () {
            if (manuallyEdited) return;
            target.value = slugify(input.value);
        });
    });

    // Confirm delete actions
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            var message = el.getAttribute('data-confirm') || 'Opravdu chcete tuto položku smazat?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

});

/**
 * Generate URL-safe slug from text (Czech diacritics safe).
 */
function slugify(text) {
    var map = {
        'á':'a','č':'c','ď':'d','é':'e','ě':'e','í':'i','ň':'n','ó':'o',
        'ř':'r','š':'s','ť':'t','ú':'u','ů':'u','ý':'y','ž':'z',
        'Á':'a','Č':'c','Ď':'d','É':'e','Ě':'e','Í':'i','Ň':'n','Ó':'o',
        'Ř':'r','Š':'s','Ť':'t','Ú':'u','Ů':'u','Ý':'y','Ž':'z'
    };

    var slug = text.toLowerCase();
    slug = slug.replace(/[áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]/g, function (ch) {
        return map[ch] || ch;
    });
    slug = slug.replace(/[^a-z0-9]+/g, '-');
    slug = slug.replace(/^-+|-+$/g, '');
    return slug;
}
