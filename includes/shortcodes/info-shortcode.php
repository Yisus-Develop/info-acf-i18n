<?php
// [info_shortcode]



/*
<!--
[info_acf] - Shortcode dinámico para mostrar secciones de campos ACF

USO GENERAL:
[info_acf section="NOMBRE_SECCION" fields="palabra1,palabra2"]

Atributos:
- section: nombre de la sección a mostrar (obligatorio)
- fields: (opcional) lista separada por comas para filtrar resultados por palabras clave

====================================
SECCIONES DISPONIBLES
====================================

1. section="ficheiro"
------------------------------------
Muestra un archivo cargado en el campo ACF 'file'.
Ejemplo:
[info_acf section="ficheiro"]

Resultado:
Ficheiro: [nombre del archivo] (enlace)

2. section="viewer"
------------------------------------
Muestra contenido del campo ACF 'viewer'.
Ejemplo:
[info_acf section="viewer"]

Resultado:
Documento: [contenido del viewer]

3. section="ficha_tecnica"
------------------------------------
Muestra los datos técnicos de tipo repeater (label + value).
Se puede filtrar por palabras clave:
[info_acf section="ficha_tecnica" fields="ph,temperatura"]

Resultado:
- Label: Valor (ahora como lista, no tabla)

4. section="membros_internos"
------------------------------------
Muestra miembros internos relacionados (tipo post object).
Filtrable por nombre:
[info_acf section="membros_internos" fields="joão,silva"]

Resultado:
João Silva – Cargo

5. section="membros_externos"
------------------------------------
Muestra miembros externos (nombre, cargo, enlace opcional).
Filtrable por nombre:
[info_acf section="membros_externos" fields="maria"]

Resultado:
Maria Dias – Cargo (con o sin enlace)

6. section="posts_relacionados"
------------------------------------
Muestra posts relacionados (tipo post object) como lista de enlaces.
Filtrable por título:
[info_acf section="posts_relacionados" fields="plantas,inovação"]

Resultado:
- [Título del post](URL)

====================================
NOTAS SOBRE EL ATRIBUTO 'fields'
====================================
- No obligatorio. Si se omite, muestra todo.
- Filtra por coincidencia parcial (no sensible a mayúsculas).
- Separa múltiples filtros con comas.

====================================
EJEMPLO DE USO CON MÚLTIPLES BLOQUES:
====================================
[info_acf section="ficheiro"]
[info_acf section="ficha_tecnica" fields="ph"]
[info_acf section="membros_internos"]

-->
*/
function info_shortcode() {
    ob_start();
    echo '<div class="project-info">';

    // SEÇÃO: INFORMAÇÃO DO PROJETO
    echo '<div class="section-info-projeto section-block">';
    echo '<h3>' . __('Informação do Projeto', 'eweb-content-functionalities') . '</h3>';
    
    
    

    // Ficheiro
    $file = get_field('file');
    if ($file) {
        echo '<div class="section-ficheiro section-block">';
        echo '<p><strong>' . __('Ficheiro', 'eweb-content-functionalities') . ':</strong> ';
        echo '<a href="' . esc_url($file['url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($file['filename']) . '</a></p>';
        echo '</div>';
    }

    // Viewer
    $viewer = get_field('viewer');
    if ($viewer) {
        echo '<div class="section-viewer section-block">';
        echo '<p><strong>' . __('Documento', 'eweb-content-functionalities') . ':</strong> ' . esc_html($viewer) . '</p>';
        echo '</div>';
    }

   // Ficha Técnica
if (have_rows('info') || get_field('fase_projeto')) {
    echo '<div class="section-ficha section-block">';
    echo '<h3>' . __('Ficha Técnica', 'eweb-content-functionalities') . '</h3>';
    echo '<div class="ficha-tecnica">';

    // ✅ Mostrar fase atual como item fixo
    $fase_atual = get_field('fase_projeto');
    if ($fase_atual) {
        echo '<div class="ficha-item">';
        echo '<span class="label"><strong>' . __('Fase do Pipeline:', 'eweb-content-functionalities') . '</strong></span> ';
        echo '<span class="value">' . esc_html($fase_atual) . '</span>';
        echo '</div>';
    }

    // Campos personalizados ACF (info repeater)
    while (have_rows('info')) : the_row();
        $row = get_sub_field('row');
        if (!empty($row['label']) || !empty($row['value'])) {
            echo '<div class="ficha-item">';
            echo '<span class="label"><strong>' . esc_html($row['label']) . ':</strong></span> ';
            echo '<span class="value">' . esc_html($row['value']) . '</span>';
            echo '</div>';
        }
    endwhile;

    echo '</div></div>';
}


    echo '</div>'; // .section-info-projeto

    // SEÇÃO: EQUIPA (Internos + Externos)
    $members = [];

    // Internos
    if (have_rows('members')) {
        while (have_rows('members')) : the_row();
            $member_post = get_sub_field('member');
            $position = get_sub_field('position');
            if ($member_post) {
                $members[] = [
                    'name' => get_the_title($member_post),
                    'position' => $position,
                    'url' => get_permalink($member_post),
                ];
            }
        endwhile;
    }

    // Externos
    if (have_rows('members_external')) {
        while (have_rows('members_external')) : the_row();
            $name = get_sub_field('member');
            $position = get_sub_field('position');
            $url = get_sub_field('url');
            $members[] = [
                'name' => $name,
                'position' => $position,
                'url' => $url ? esc_url($url) : '',
            ];
        endwhile;
    }

    if (!empty($members)) {
        echo '<div class="section-miembros section-block">';
        echo '<h3>' . esc_html__('Equipa', 'eweb-content-functionalities') . '</h3>';

        // Encabezados visuales como si fueran tabla
        echo '<div class="members-table">';
        echo '<div class="row-header">';
        echo '<div class="col"><strong>' . esc_html__('Membro', 'eweb-content-functionalities') . '</strong></div>';
        echo '<div class="col"><strong>' . esc_html__('Posição', 'eweb-content-functionalities') . '</strong></div>';
        echo '</div>';

        foreach ($members as $m) {
            echo '<div class="row">';
            echo '<div class="col member-name">';
            if (!empty($m['url'])) {
                echo '<a href="' . esc_url($m['url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($m['name']) . '</a>';
            } else {
                echo esc_html($m['name']);
            }
            echo '</div>';
            echo '<div class="col member-role">' . esc_html($m['position']) . '</div>';
            echo '</div>';
        }

        echo '</div>'; // .members-table
        echo '</div>'; // .section-miembros
    }

    // POSTS RELACIONADOS
    if (have_rows('posts')) {
        echo '<div class="section-posts section-block">';
        echo '<h3>' . __('Posts Relacionados', 'eweb-content-functionalities') . '</h3>';
        echo '<ul class="no-bullets">';
        while (have_rows('posts')) : the_row();
            $post = get_sub_field('post');
            if ($post) {
                echo '<li><a href="' . esc_url(get_permalink($post)) . '">' . esc_html(get_the_title($post)) . '</a></li>';
            }
        endwhile;
        echo '</ul></div>';
    }

    echo '</div>'; // .project-info

    return ob_get_clean();
}
add_shortcode('info_shortcode', 'info_shortcode');

