<?php

// Função para calcular tempo estimado de leitura
function obter_tempo_leitura($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $tempo_leitura = ceil($word_count / 200); // 200 palavras por minuto

    // Frase traduzível com gettext
    return sprintf(
        esc_html__('%d min de leitura', 'info-acf-plugin'),
        $tempo_leitura
    );
}

// Shortcode para usar no Elementor
function shortcode_tempo_leitura() {
    return obter_tempo_leitura();
}
add_shortcode('tempo_leitura', 'shortcode_tempo_leitura');
