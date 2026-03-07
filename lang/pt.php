<?php

/**
 * Traduções em Português
 *
 * Chaves em notação de grupo: 'secção.elemento'
 * Substituições com :placeholder: 'Olá, :name'
 */
return [

    // Title and description for SEO
    'seo.title'       => 'Luany',
    'seo.description' => 'Framework PHP MVC compilada em AST',

    // ── Navbar ────────────────────────────────────────────────
    'nav.home'   => 'Início',
    'nav.docs'   => 'Documentação',
    'nav.github' => 'GitHub',

    // ── Theme ─────────────────────────────────────────────────
    'theme.to_light' => 'Mudar para modo claro',
    'theme.to_dark'  => 'Mudar para modo escuro',

    // ── Hero ──────────────────────────────────────────────────
    'hero.eyebrow'       => 'v0.2 — Stack pronta · APP_ENV=development',
    'hero.title_line1'   => 'A sua aplicação está em',
    'hero.title_accent'  => 'execução',
    'hero.subtitle'      => 'Crie um controlador, defina uma rota e lance a sua primeira página de visualização.<br>Tudo está ligado — começa a construir.',
    'hero.cta_primary'   => 'Ler a Documentação →',
    'hero.cta_secondary' => 'GitHub',
    'hero.stat_regex'    => 'regex',
    'hero.stat_tests'    => 'testes',
    'hero.stat_compiler' => 'compilador',

    // ── Playground ────────────────────────────────────────────
    'playground.eyebrow'       => 'Motor de Templates LTE',
    'playground.title'         => 'Vê o compilador em acção',
    'playground.lead'          => 'O LTE analisa templates numa AST e emite PHP optimizado. Zero regex. Output determinístico.',
    'playground.tab_foreach'   => '&#64;foreach',
    'playground.tab_escape'    => '&#123;&#123; &#125;&#125; vs &#123;!! !!&#125;',
    'playground.tab_compiled'  => 'PHP Compilado',
    'playground.lte_template'  => 'Template LTE',
    'playground.lte_source'    => 'Fonte LTE',
    'playground.server_output' => 'Saída do Servidor',
    'playground.php_rendered'  => 'renderizado em PHP',
    'playground.compiled_php'  => 'PHP Compilado',
    'playground.no_users'      => 'Nenhum utilizador encontrado.',

    // ── Pipeline ──────────────────────────────────────────────
    'pipeline.eyebrow' => 'Ciclo de Vida do Request',
    'pipeline.title'   => 'Explícito por design',

    'pipeline.step1.name' => 'Request::fromGlobals()',
    'pipeline.step1.desc' => 'Superglobais PHP capturados num objecto Request tipado e imutável. Método, URI, headers, body — tudo normalizado.',

    'pipeline.step2.name' => 'Pipeline de Middleware Global',
    'pipeline.step2.desc' => 'Cada request passa pelo middleware global antes do routing. Interrupção antecipada em qualquer ponto. CSRF, autenticação, rate limiting — tudo aqui.',

    'pipeline.step3.name' => 'Route::handle()',
    'pipeline.step3.desc' => 'O Router faz correspondência do método + URI. Rotas nomeadas, grupos, rotas resource, pipeline de middleware por rota — tudo resolvido aqui.',

    'pipeline.step4.name' => 'Controller → Motor LTE',
    'pipeline.step4.desc' => 'O Controller retorna um valor. O LTE compila a view via AST, faz cache do PHP compilado. Auto-reload em debug, cache persistente em produção.',

    'pipeline.step5.name' => 'Response::send()',
    'pipeline.step5.desc' => 'Response tipado com código de status, headers e body. Send envia para o cliente. Kernel::terminate() executa a limpeza pós-envio.',

    // ── Features ──────────────────────────────────────────────
    'features.eyebrow' => 'O que está incluído',
    'features.title'   => 'Engenhado para clareza',
    'features.empty'   => 'Nenhuma funcionalidade encontrada.',

    'features.ast.name' => 'Motor de Templates AST',
    'features.ast.desc' => 'O LTE compila via AST — zero regex, output previsível, CSS e JS colocalizados com blocos @style e @script.',

    'features.pipeline.name' => 'Pipeline de Middleware',
    'features.pipeline.desc' => 'Ciclo de vida do request explícito. Middleware global e por rota com suporte total a interrupção antecipada. Sem magia.',

    'features.providers.name' => 'Service Providers',
    'features.providers.desc' => 'Ciclo de boot em duas fases. Todos os register() completam antes de qualquer boot() — dependências entre provedores sempre seguras.',

    'features.csrf.name' => 'Protecção CSRF',
    'features.csrf.desc' => 'Verificação automática de token em cada request que altera estado. Uma directiva @csrf no formulário — pronto.',

    // ── Next Steps ────────────────────────────────────────────
    'nextsteps.eyebrow'  => 'Referência CLI',
    'nextsteps.title'    => 'O que vem a seguir?',
    'nextsteps.lead'     => 'O teu ambiente está pronto. Corre estes comandos para criar A sua primeira feature.',

    'nextsteps.cmd1'  => 'php luany make:controller Nome',
    'nextsteps.desc1' => 'Cria um novo controller em app/Controllers/',
    'nextsteps.cmd2'  => 'php luany make:model Nome',
    'nextsteps.desc2' => 'Cria uma classe model em app/Models/',
    'nextsteps.cmd3'  => 'php luany make:migration nome',
    'nextsteps.desc3' => 'Gera um ficheiro de migração com timestamp',
    'nextsteps.cmd4'  => 'php luany migrate',
    'nextsteps.desc4' => 'Executa todas as migrações pendentes',

    'nextsteps.docs'    => 'Ler a Documentação →',
    'nextsteps.github'  => 'Ver no GitHub',
    'nextsteps.version' => 'luany/framework v0.2 · Licença MIT',

    // ── Footer ────────────────────────────────────────────────
    'footer.tagline'       => 'Framework PHP MVC compilada em AST.<br>Ciclo de vida explícito. Zero regex.',
    'footer.col_ecosystem' => 'Ecossistema',
    'footer.col_resources' => 'Recursos',
    'footer.col_legal'     => 'Legal',
    'footer.docs'          => 'Documentação',
    'footer.packagist'     => 'Packagist',
    'footer.changelog'     => 'Changelog',
    'footer.issues'        => 'Issues',
    'footer.license'       => 'Licença MIT',
    'footer.readme'        => 'README',
    'footer.built_with'    => 'Feito com Luany Framework',
    'footer.copyright'     => '© :year :name — Licença MIT',

    // ── Error 404 ─────────────────────────────────────────────
    '404.eyebrow'     => '404 — Página Não Encontrada',
    '404.description' => 'A página que procuras não existe<br>ou foi movida permanentemente.',
    '404.back_home'   => '← Voltar ao Início',
    '404.previous'    => 'Página anterior',
    '404.meta_label'  => 'URI do Request',

    // ── Error 500 ─────────────────────────────────────────────
    '500.eyebrow'     => '500 — Erro Interno do Servidor',
    '500.description' => 'Algo correu mal do nosso lado.<br>Fomos notificados e estamos a trabalhar para resolver.',
    '500.back_home'   => '← Voltar ao Início',
    '500.try_again'   => 'Tentar novamente',

];