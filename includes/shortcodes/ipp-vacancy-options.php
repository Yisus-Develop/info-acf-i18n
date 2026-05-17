<?php
// [ipp_vacancy_options]

// Añadir columna "Activo" en listado admin para ipp_vacancy
function ipp_vacancies_add_active_column($columns)
{
	// Insertar columna después del título o al final
	$columns_new = [];
	foreach ($columns as $key => $title) {
		$columns_new[$key] = $title;
		if ($key === 'title') {
			$columns_new['ipp_vacancy_active'] = 'Activo'; // Nombre de la columna
		}
	}
	return $columns_new;
}
add_filter('manage_ipp_vacancy_posts_columns', 'ipp_vacancies_add_active_column');

// Mostrar contenido en la columna "Activo"
function ipp_vacancies_show_active_column_content($column, $post_id)
{
	if ($column === 'ipp_vacancy_active') {
		$activo = get_field('ipp_vacancy_status', $post_id);
		if ($activo) {
			echo '<span style="color:green;font-weight:bold;">Sí</span>';
		} else {
			echo '<span style="color:red;">No</span>';
		}
	}
}
add_action('manage_ipp_vacancy_posts_custom_column', 'ipp_vacancies_show_active_column_content', 10, 2);

// Hacer la columna "Activo" ordenable (opcional)
function ipp_vacancies_active_column_sortable($columns)
{
	$columns['ipp_vacancy_active'] = 'ipp_vacancy_status';
	return $columns;
}
add_filter('manage_edit-ipp_vacancy_sortable_columns', 'ipp_vacancies_active_column_sortable');

// Añadir soporte para ordenar por meta key 'ipp_vacancy_status' (opcional)
function ipp_vacancies_orderby($query)
{
	if (!is_admin()) return;

	$orderby = $query->get('orderby');
	if ('ipp_vacancy_status' === $orderby) {
		$query->set('meta_key', 'ipp_vacancy_status');
		$query->set('orderby', 'meta_value_num'); // true_false guarda 1/0
	}
}
add_action('pre_get_posts', 'ipp_vacancies_orderby');




// 1. Shortcode para listar opciones de vacantes activas en select
function ipp_vacancy_options_shortcode() {
    $args = array(
        'post_type'      => 'ipp_vacancy',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'ipp_vacancy_status',
                'value'   => 1,        // solo activas
                'compare' => '='
            ),
        ),
    );

    $query = new WP_Query($args);
    $options = '';

    // Capturamos el parámetro de la URL
    $selected_vacancy = isset($_GET['vacancy_id']) ? intval($_GET['vacancy_id']) : 0;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id    = get_the_ID();
            $title = get_the_title();

            $selected = ($id === $selected_vacancy) ? 'selected' : '';

            $options .= "<option value='{$id}' {$selected}>{$title}</option>";
        }
        wp_reset_postdata();
    }

    return $options;
}
add_shortcode('ipp_vacancy_options', 'ipp_vacancy_options_shortcode');



// 2. Reemplazar en el mail de CF7 el ID de la vacante por su título
add_filter('wpcf7_posted_data', function ($posted_data) {
	if (!empty($posted_data['ipp-vacancy']) && is_numeric($posted_data['ipp-vacancy'])) {
		$vac_id = intval($posted_data['ipp-vacancy']);
		$vac_post = get_post($vac_id);

		if ($vac_post && $vac_post->post_type === 'ipp_vacancy') {
			$posted_data['ipp-vacancy'] = $vac_post->post_title;
		}
	}

	return $posted_data;
});
add_action('wp_footer', 'ipp_toggle_content_html');
function ipp_toggle_content_html()
{
?>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const pairs = [
				{ btnId: 'ipp-toggle-btn-1', targetId: 'ipp-toggle-target-1' },
				{ btnId: 'ipp-toggle-btn-2', targetId: 'ipp-toggle-target-2' },
				{ btnId: 'ipp-toggle-btn-3', targetId: 'ipp-toggle-target-3' }
			];

			const allTargets = pairs.map(pair => document.getElementById(pair.targetId));

			pairs.forEach(pair => {
				const btn = document.getElementById(pair.btnId);
				const target = document.getElementById(pair.targetId);

				if (btn && target) {
					btn.addEventListener('click', function () {
						const isVisible = target.style.display === 'block';

						// Cierra todos los targets
						allTargets.forEach(t => {
							if (t) t.style.display = 'none';
						});

						// Si no estaba visible antes, lo mostramos (toggle real)
						if (!isVisible) {
							target.style.display = 'block';
						}
					});
				}
			});
		});
	</script>
<?php
}

