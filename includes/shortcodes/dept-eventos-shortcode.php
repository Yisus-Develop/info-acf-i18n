<?php
// [dept_eventos]

function shortcode_eventos_do_departamento() {
    global $post;
    if (!$post) return '';

    $output = '<div class="dept-section event_dep">';
   // $output .= '<h3>' . __('Eventos', 'info-acf-plugin') . '</h3>';
    $output .= '<ul>';

    if (have_rows('posts', $post->ID)) {
        while (have_rows('posts', $post->ID)) {
            the_row();
            $evento = get_sub_field('post');
            if ($evento) {
                $output .= '<li><a href="' . esc_url(get_permalink($evento)) . '">' . esc_html(get_the_title($evento)) . '</a></li>';
            }
        }
    } else {
        $output .= '<li>' . __('Nenhum evento encontrado.', 'info-acf-plugin') . '</li>';
    }

    $output .= '</ul></div>';
    return $output;
}
add_shortcode('dept_eventos', 'shortcode_eventos_do_departamento');
