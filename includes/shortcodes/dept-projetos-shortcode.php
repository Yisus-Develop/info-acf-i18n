<?php
// [dept_projetos]

function shortcode_projetos_do_departamento() {
    global $post;
    if (!$post) return '';

    $output = '<div class="dept-section projet_dep">';
 //   $output .= '<h3>' . __('Projetos', 'info-acf-plugin') . '</h3>';
    $output .= '<ul>';

    if (have_rows('projects', $post->ID)) {
        while (have_rows('projects', $post->ID)) {
            the_row();
            $project = get_sub_field('project');
            if ($project) {
                $output .= '<li><a href="' . esc_url(get_permalink($project)) . '">' . esc_html(get_the_title($project)) . '</a></li>';
            }
        }
    } else {
        $output .= '<li>' . __('Nenhum projeto encontrado.', 'info-acf-plugin') . '</li>';
    }

    $output .= '</ul></div>';
    return $output;
}
add_shortcode('dept_projetos', 'shortcode_projetos_do_departamento');

