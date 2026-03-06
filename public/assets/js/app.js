/**
 * Luany Skeleton — Global JavaScript
 *
 * Intentionally minimal.
 * Component JS lives inside .lte files via @script/@endscript.
 * LTE AssetStack deduplicates and renders them at @scripts in the layout.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Expose CSRF token globally for fetch/XHR requests
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) window.csrfToken = meta.getAttribute('content');
});