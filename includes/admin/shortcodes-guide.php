<?php
defined('ABSPATH') || exit;

/**
 * Render da guia de shortcodes (usado no widget e no aviso pós-ativação)
 * $highlight = true → mostra uma moldura de destaque
 */
function iap_render_shortcodes_guide($highlight = false) {
    if ($highlight) {
        echo '<div style="border:2px solid #2271b1; padding:12px; margin-bottom:12px; background:#f0f6fc;">';
        echo '<p style="margin:0 0 6px 0;"><strong>Obrigado por ativar o Info ACF Plugin!</strong></p>';
        echo '<p style="margin:0;">Aqui tens uma guia rápida com todos os shortcodes disponíveis e respetivos atributos.</p>';
        echo '</div>';
    }

    echo '<table class="widefat striped" style="max-width:980px">';
    echo '<thead><tr>';
    echo '<th>Shortcode</th>';
    echo '<th>Descrição</th>';
    echo '<th>Atributos</th>';
    echo '<th>Dependências</th>';
    echo '<th>Exemplo</th>';
    echo '</tr></thead><tbody>';

    // 1) Vagas: grelha + popup de candidatura à vaga
    echo '<tr>';
    echo '<td><code>[ipp_vacancies_grid]</code></td>';
    echo '<td>Mostra a grelha de vagas e abre o popup para candidatura a essa vaga.</td>';
    echo '<td><code>form</code> = ID do CF7 (opcional; existe predefinição)</td>';
    echo '<td>ACF, CPT <code>ipp_vacancy</code>, Contact Form 7 (para o popup)</td>';
    echo '<td><code>[ipp_vacancies_grid form="6e5f724"]</code></td>';
    echo '</tr>';

    // 2) Popup de candidatura espontânea
    echo '<tr>';
    echo '<td><code>[ipp_popup_espontaneo]</code></td>';
    echo '<td>Botão + popup para candidatura espontânea.</td>';
    echo '<td><code>form</code>, <code>btn_text</code>, <code>title</code></td>';
    echo '<td>Contact Form 7</td>';
    echo '<td><code>[ipp_popup_espontaneo form="6f7a3f8" btn_text="Candidatura Espontânea" title="Candidatura Espontânea"]</code></td>';
    echo '</tr>';

    // 3) Opções de vagas (para CF7) – se existir no teu plugin
    echo '<tr>';
    echo '<td><code>[ipp_vacancy_options]</code></td>';
    echo '<td>Lista/seleção de vagas para integrar em formulários (CF7).</td>';
    echo '<td>(conforme implementação: filtros por departamento, estado, etc.)</td>';
    echo '<td>ACF, CPT <code>ipp_vacancy</code></td>';
    echo '<td><code>[ipp_vacancy_options]</code></td>';
    echo '</tr>';

    // 4) Membros por departamento
    echo '<tr>';
    echo '<td><code>[departamento_miembros]</code></td>';
    echo '<td>Mostra membros por departamento (campos/relacionamentos ACF).</td>';
    echo '<td>(conforme implementação: <code>dept_id</code>, <code>layout</code>, <code>limit</code>…)</td>';
    echo '<td>ACF, CPT(s) de membros/departamentos</td>';
    echo '<td><code>[departamento_miembros]</code></td>';
    echo '</tr>';

    // 4.1) Cartão Digital / Popup do membro
    echo '<tr>';
    echo '<td><code>[member_contact_popup]</code></td>';
    echo '<td>Botão que abre o <strong>Cartão Digital</strong> com contactos do membro, QR (iPhone/Android) e download do .vcf.</td>';
    echo '<td><code>id</code> (opcional, usa o post atual se omitido), <code>btn</code> (texto do botão)</td>';
    echo '<td>ACF, CPT <code>iplantprotect_member</code>, pasta gravável <code>/wp-content/vcards/</code></td>';
    echo '<td><code>[member_contact_popup id="123" btn="Cartão Digital"]</code></td>';
    echo '</tr>';

    // 5) Eventos por departamento
    echo '<tr>';
    echo '<td><code>[dept_eventos]</code></td>';
    echo '<td>Mostra eventos associados a um departamento.</td>';
    echo '<td>(conforme implementação: <code>dept_id</code>, <code>date_from</code>/<code>date_to</code>…)</td>';
    echo '<td>ACF, CPT de eventos/departamentos</td>';
    echo '<td><code>[dept_eventos]</code></td>';
    echo '</tr>';

    // 6) Projetos por departamento
    echo '<tr>';
    echo '<td><code>[dept_projetos]</code></td>';
    echo '<td>Lista projetos de um departamento.</td>';
    echo '<td>(conforme implementação)</td>';
    echo '<td>ACF, CPT de projetos/departamentos</td>';
    echo '<td><code>[dept_projetos]</code></td>';
    echo '</tr>';

    // 7) Shortcodes genéricos ACF
    echo '<tr>';
    echo '<td><code>[info_acf_list]</code></td>';
    echo '<td>Lista posts de um CPT mostrando campos ACF.</td>';
    echo '<td><code>post_type</code>, <code>fields</code>, <code>orderby</code>, <code>order</code>, <code>posts_per_page</code>…</td>';
    echo '<td>ACF</td>';
    echo '<td><code>[info_acf_list post_type="post" fields="campo1,campo2"]</code></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><code>[info_acf_detail]</code></td>';
    echo '<td>Ficha/detalhe de um post com campos ACF.</td>';
    echo '<td><code>id</code>, <code>fields</code></td>';
    echo '<td>ACF</td>';
    echo '<td><code>[info_acf_detail id="123" fields="campo1,campo2"]</code></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><code>[info_acf_table]</code></td>';
    echo '<td>Tabela com valores de campos ACF.</td>';
    echo '<td><code>id</code>, <code>fields</code></td>';
    echo '<td>ACF</td>';
    echo '<td><code>[info_acf_table id="123" fields="campo1,campo2"]</code></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><code>[info_acf_map]</code></td>';
    echo '<td>Mapa a partir de campo ACF (mapa ou lat/lon).</td>';
    echo '<td><code>id</code>, <code>field</code></td>';
    echo '<td>ACF</td>';
    echo '<td><code>[info_acf_map id="123" field="localizacao"]</code></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><code>[info_acf_gallery]</code></td>';
    echo '<td>Galeria de imagens a partir do ACF (galeria ou repetidor).</td>';
    echo '<td><code>id</code>, <code>field</code></td>';
    echo '<td>ACF</td>';
    echo '<td><code>[info_acf_gallery id="123" field="galeria"]</code></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><code>[info_acf_search]</code></td>';
    echo '<td>Pesquisa por CPT com filtros em campos ACF.</td>';
    echo '<td><code>post_type</code>, <code>fields</code>…</td>';
    echo '<td>ACF</td>';
    echo '<td><code>[info_acf_search post_type="post" fields="campo1,campo2"]</code></td>';
    echo '</tr>';

    // 8) Tempo de leitura
    echo '<tr>';
    echo '<td><code>[tempo_leitura]</code></td>';
    echo '<td>Mostra o tempo estimado de leitura do conteúdo.</td>';
    echo '<td>(sem atributos, salvo implementação específica)</td>';
    echo '<td>-</td>';
    echo '<td><code>[tempo_leitura]</code></td>';
    echo '</tr>';

    // 9) Pipeline Bio (novo)
    echo '<tr>';
    echo '<td><code>[pipeline_bio_pipeline]</code></td>';
    echo '<td>Mostra o pipeline de projetos <strong>Bio</strong> (ACF pipeline_type = bio).</td>';
    echo '<td>(sem atributos)</td>';
    echo '<td>ACF, CPT <code>pipeline</code>, campos “bio”</td>';
    echo '<td><code>[pipeline_bio_pipeline]</code></td>';
    echo '</tr>';

    // 10) Pipeline Digital (novo)
    echo '<tr>';
    echo '<td><code>[pipeline_digital_pipeline]</code></td>';
    echo '<td>Mostra o pipeline de projetos <strong>Digital</strong> (ACF pipeline_type = digital).</td>';
    echo '<td>(sem atributos)</td>';
    echo '<td>ACF, CPT <code>pipeline</code>, campos “digital”</td>';
    echo '<td><code>[pipeline_digital_pipeline]</code></td>';
    echo '</tr>';
    
    // 11) Cartão Digital / Popup do membro
echo '<tr>';
echo '<td><code>[member_contact_popup]</code></td>';
echo '<td>Botão que abre o <strong>Cartão Digital</strong> com contactos do membro, QR (iPhone/Android) e download do .vcf.</td>';
echo '<td><code>id</code> (opcional, usa o post atual se omitido), <code>btn</code> (texto do botão)</td>';
echo '<td>ACF, CPT <code>iplantprotect_member</code>, pasta gravável <code>/wp-content/vcards/</code></td>';
echo '<td><code>[member_contact_popup id="123" btn="Cartão Digital"]</code><br><code>[member_contact_popup btn="Cartão Digital"]</code></td>';
echo '</tr>';


    echo '</tbody></table>';

    echo '<p style="margin-top:10px;"><em>Requisitos: ACF ativo (obrigatório para a maioria). Contact Form 7 recomendado/necessário para os popups.</em></p>';


}

/** Widget permanente no Painel (Dashboard) */
function iap_register_dashboard_widget() {
    wp_add_dashboard_widget(
        'iap_shortcodes_widget',
        'Info ACF Plugin – Guia de Shortcodes',
        'iap_render_shortcodes_guide'
    );
}
add_action('wp_dashboard_setup', 'iap_register_dashboard_widget');

/** Ao ativar, guardar transient para mostrar o aviso de destaque uma única vez */
function iap_on_activate_shortcodes_guide() {
    set_transient('iap_activation_shortcodes_guide', 1, 30 * MINUTE_IN_SECONDS);
}
register_activation_hook(IAP_PLUGIN_FILE, 'iap_on_activate_shortcodes_guide');

/** Aviso de destaque no admin apenas após ativação */
function iap_admin_activation_shortcodes_notice() {
    if (!get_transient('iap_activation_shortcodes_guide')) return;
    if (!current_user_can('manage_options')) return;

    delete_transient('iap_activation_shortcodes_guide');

    echo '<div class="notice notice-info is-dismissible">';
    iap_render_shortcodes_guide(true);
    echo '</div>';
}
add_action('admin_notices', 'iap_admin_activation_shortcodes_notice');
