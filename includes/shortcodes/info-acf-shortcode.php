<?php
// [info_acf]

function info_acf_section_shortcode($atts) {
	$atts = shortcode_atts([
		'section' => '',
		'fields' => '',
	], $atts, 'info_acf');

	$filter_fields = array_filter(array_map('trim', explode(',', $atts['fields'])));
	$filter_fields = array_map('mb_strtolower', $filter_fields); // para comparar

	ob_start();

	switch ($atts['section']) {
		case 'ficheiro':
			$file = get_field('file');
			if ($file) {
				echo '<p><strong>' . __('Ficheiro:', 'eweb-content-functionalities') . '</strong> <a href="' . esc_url($file['url']) . '" target="_blank">' . __('Ver', 'eweb-content-functionalities') . '</a></p>';

			}
			break;

		case 'viewer':
			$viewer = get_field('viewer');
			if ($viewer) {
				echo '<p><strong>Documento:</strong> ' . esc_html($viewer) . '</p>';
			}
			break;

				case 'ficha_tecnica':
			if (have_rows('info')) {
				echo '<ul class="ficha-lista">';
				while (have_rows('info')) : the_row();
					$row = get_sub_field('row');
					$label = $row['label'] ?? '';
					$value = $row['value'] ?? '';

					$label_lc = mb_strtolower($label);
					$match = empty($filter_fields);
					foreach ($filter_fields as $filter) {
						if (strpos($label_lc, $filter) !== false) {
							$match = true;
							break;
						}
					}
					if (!$match) continue;

					if ($label || $value) {
						echo '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</li>';
					}
				endwhile;
				echo '</ul>';
			}
			break;


		case 'membros_internos':
			if (have_rows('members')) {
				echo '<h3>Membros Internos</h3><ul>';
				while (have_rows('members')) : the_row();
					$member = get_sub_field('member');
					$position = get_sub_field('position');
					$title = $member ? get_the_title($member) : '';

					$title_lc = mb_strtolower($title);
					$match = empty($filter_fields);
					foreach ($filter_fields as $filter) {
						if (strpos($title_lc, $filter) !== false) {
							$match = true;
							break;
						}
					}
					if (!$match) continue;

					if ($member) {
						echo '<li><strong>' . esc_html($title) . '</strong>';
						if ($position) echo ' – ' . esc_html($position);
						echo '</li>';
					}
				endwhile;
				echo '</ul>';
			}
			break;

		case 'membros_externos':
			if (have_rows('members_external')) {
				echo '<h3>Membros Externos</h3><ul>';
				while (have_rows('members_external')) : the_row();
					$name = get_sub_field('member');
					$position = get_sub_field('position');
					$url = get_sub_field('url');

					$name_lc = mb_strtolower($name);
					$match = empty($filter_fields);
					foreach ($filter_fields as $filter) {
						if (strpos($name_lc, $filter) !== false) {
							$match = true;
							break;
						}
					}
					if (!$match) continue;

					echo '<li>';
					if ($url) {
						echo '<a href="' . esc_url($url) . '" target="_blank"><strong>' . esc_html($name) . '</strong></a>';
					} else {
						echo '<strong>' . esc_html($name) . '</strong>';
					}
					if ($position) echo ' – ' . esc_html($position);
					echo '</li>';
				endwhile;
				echo '</ul>';
			}
			break;

		case 'posts_relacionados':
			if (have_rows('posts')) {
				echo '<h3>Posts Relacionados</h3><ul>';
				while (have_rows('posts')) : the_row();
					$post = get_sub_field('post');
					$title = $post ? get_the_title($post) : '';

					$title_lc = mb_strtolower($title);
					$match = empty($filter_fields);
					foreach ($filter_fields as $filter) {
						if (strpos($title_lc, $filter) !== false) {
							$match = true;
							break;
						}
					}
					if (!$match) continue;

					if ($post) {
						echo '<li><a href="' . esc_url(get_permalink($post)) . '">' . esc_html($title) . '</a></li>';
					}
				endwhile;
				echo '</ul>';
			}
			break;

		default:
			echo '<p><em>Seção inválida ou não especificada.</em></p>';
			break;
	}

	return '<div class="info-acf-section">' . ob_get_clean() . '</div>';

}
add_shortcode('info_acf', 'info_acf_section_shortcode');
