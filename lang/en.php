<?php

/**
 * English translations
 *
 * Keys use dot-group notation: 'section.element'
 * Replacements use :placeholder syntax: 'Hello, :name'
 */
return [

    // Title and description for SEO
    'seo.title'       => 'Luany',
    'seo.description' => 'AST-compiled PHP MVC Framework',

    // ── Navbar ────────────────────────────────────────────────
    'nav.home'   => 'Home',
    'nav.docs'   => 'Docs',
    'nav.github' => 'GitHub',

    // ── Theme ─────────────────────────────────────────────────
    'theme.to_light' => 'Switch to light mode',
    'theme.to_dark'  => 'Switch to dark mode',

    // ── Hero ──────────────────────────────────────────────────
    'hero.eyebrow'       => 'v1.0 — Stack wired · APP_ENV=development',
    'hero.title_line1'   => 'Your application is',
    'hero.title_accent'  => 'running.',
    'hero.subtitle'      => 'Create a controller, define a route, ship your first view page.<br>Everything is wired — start building.',
    'hero.cta_primary'   => 'Read the Docs →',
    'hero.cta_secondary' => 'GitHub',
    'hero.stat_regex'    => 'regex',
    'hero.stat_tests'    => '252',
    'hero.stat_compiler' => 'compiler',

    // ── Playground ────────────────────────────────────────────
    'playground.eyebrow'       => 'LTE Template Engine',
    'playground.title'         => 'See the compiler in action',
    'playground.lead'          => 'LTE parses templates into an AST and emits optimised PHP. Zero regex. Deterministic output.',
    'playground.tab_foreach'   => '&#64;foreach',
    'playground.tab_escape'    => '&#123;&#123; &#125;&#125; vs &#123;!! !!&#125;',
    'playground.tab_compiled'  => 'Compiled PHP',
    'playground.lte_template'  => 'LTE Template',
    'playground.lte_source'    => 'LTE Source',
    'playground.server_output' => 'Server Output',
    'playground.php_rendered'  => 'PHP rendered',
    'playground.compiled_php'  => 'Compiled PHP',
    'playground.no_users'      => 'No users found.',

    // ── Pipeline ──────────────────────────────────────────────
    'pipeline.eyebrow' => 'Request Lifecycle',
    'pipeline.title'   => 'Explicit by design',

    'pipeline.step1.name' => 'Request::fromGlobals()',
    'pipeline.step1.desc' => 'PHP superglobals captured into a typed, immutable Request object. Method, URI, headers, body — all normalised.',

    'pipeline.step2.name' => 'Global Middleware Pipeline',
    'pipeline.step2.desc' => 'Every request passes through global middleware before routing. Short-circuit at any point. CSRF, auth, rate limiting — all here.',

    'pipeline.step3.name' => 'Route::handle()',
    'pipeline.step3.desc' => 'Router matches method + URI. Named routes, route groups, resource routes, per-route middleware pipeline — all resolved here.',

    'pipeline.step4.name' => 'Controller → LTE Engine',
    'pipeline.step4.desc' => 'Controller returns a value. LTE compiles the view via AST, caches compiled PHP. Auto-reload in debug mode, persistent cache in production.',

    'pipeline.step5.name' => 'Response::send()',
    'pipeline.step5.desc' => 'Typed Response with status code, headers, and body. Send flushes to the client. Kernel::terminate() runs post-send cleanup.',

    // ── Features ──────────────────────────────────────────────
    'features.eyebrow' => "What's included",
    'features.title'   => 'Engineered for clarity',
    'features.empty'   => 'No features found.',

    'features.ast.name' => 'AST Template Engine',
    'features.ast.desc' => 'LTE compiles via AST — zero regex, predictable output, collocated CSS and JS with @style and @script blocks.',

    'features.pipeline.name' => 'Middleware Pipeline',
    'features.pipeline.desc' => 'Explicit request lifecycle. Global and per-route middleware with full short-circuit support. No magic.',

    'features.providers.name' => 'Service Providers',
    'features.providers.desc' => 'Two-phase boot lifecycle. All register() calls complete before any boot() runs — cross-provider dependencies always safe.',

    'features.csrf.name' => 'CSRF Protection',
    'features.csrf.desc' => 'Automatic token verification on every state-changing request. One @csrf directive in your form — done.',

    // ── Next Steps ────────────────────────────────────────────
    'nextsteps.eyebrow'  => 'CLI Reference',
    'nextsteps.title'    => "What's next?",
    'nextsteps.lead'     => 'Your environment is wired. Run these to scaffold your first feature.',

    'nextsteps.cmd1'  => 'luany make:controller Name',
    'nextsteps.desc1' => 'Scaffold a new controller in app/Controllers/',
    'nextsteps.cmd2'  => 'luany make:model Name',
    'nextsteps.desc2' => 'Create a model class in app/Models/',
    'nextsteps.cmd3'  => 'luany make:migration name',
    'nextsteps.desc3' => 'Generate a timestamped migration file',
    'nextsteps.cmd4'  => 'luany migrate',
    'nextsteps.desc4' => 'Run all pending database migrations',

    'nextsteps.docs'    => 'Read the Docs →',
    'nextsteps.github'  => 'View on GitHub',
    'nextsteps.version' => 'luany/framework v1.0 · MIT License',

    // ── Footer ────────────────────────────────────────────────
    'footer.tagline'       => 'AST-compiled PHP MVC Framework.<br>Explicit lifecycle. Zero regex.',
    'footer.col_ecosystem' => 'Ecosystem',
    'footer.col_resources' => 'Resources',
    'footer.col_legal'     => 'Legal',
    'footer.docs'          => 'Documentation',
    'footer.packagist'     => 'Packagist',
    'footer.changelog'     => 'Changelog',
    'footer.issues'        => 'Issues',
    'footer.license'       => 'MIT License',
    'footer.readme'        => 'README',
    'footer.built_with'    => 'Built with Luany Framework',
    'footer.copyright'     => '© :year :name — MIT License',

    // ── Error 404 ─────────────────────────────────────────────
    '404.eyebrow'     => '404 — Page Not Found',
    '404.description' => 'The page you are looking for does not exist<br>or has been permanently moved.',
    '404.back_home'   => '← Back to Home',
    '404.previous'    => 'Previous page',
    '404.meta_label'  => 'Request URI',

    // ── Error 500 ─────────────────────────────────────────────
    '500.eyebrow'     => '500 — Internal Server Error',
    '500.description' => 'Something went wrong on our end.<br>We have been notified and are working to fix it.',
    '500.back_home'   => '← Back to Home',
    '500.try_again'   => 'Try again',

];