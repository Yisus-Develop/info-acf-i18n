<?php
// [ipp_vacancies_grid form="6e5f724"]

function ipp_shortcode_vacancies_grid($atts = []) {
    $atts = shortcode_atts([
        'form' => '6e5f724', // ID CF7 por defecto para "Candidatura a Vaga"
    ], $atts, 'ipp_vacancies_grid');

    if (!class_exists('ACF')) {
        return '<p>' . esc_html__('ACF plugin is required.', 'info-acf-plugin') . '</p>';
    }

    $args = [
        'post_type'      => 'ipp_vacancy',
        'posts_per_page' => -1,
        'meta_key'       => 'ipp_vacancy_status',
        'meta_value'     => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p class="ipp-vacancies nãovagas">' . esc_html__('Não há vagas disponíveis no momento.', 'info-acf-plugin') . '</p>';
    }

    ob_start(); ?>

    <div class="ipp-vacancies-grid-style">
        <?php while ($query->have_posts()) : $query->the_post();
            $position   = get_field('ipp_vacancy_position') ?: get_the_title();
            $department = get_field('ipp_vacancy_department');
            $date_limit = get_field('ipp_vacancy_date_limit');
            $reference  = get_field('ipp_vacancy_ref');
            $notice     = get_field('ipp_vacancy_notice');
            $url        = get_field('url');
            $notice_url = is_array($notice) && !empty($notice['url']) ? esc_url($notice['url']) : '';
        ?>
            <div class="ipp-vacancy-card">
                <div class="vacancy-header">
                    <h4 class="ipp-vacancy-heading"><?php echo esc_html__('Recrutamento', 'info-acf-plugin'); ?></h4>
                </div>

                <ul class="ipp-vacancy-info">
                    <li><strong><?php echo esc_html__('Posição:', 'info-acf-plugin'); ?></strong>
                        <span class="vacancy-position"><?php echo esc_html($position); ?></span></li>
                    <?php if ($department): ?>
                        <li><strong><?php echo esc_html__('Departamento:', 'info-acf-plugin'); ?></strong>
                            <span class="ipp-department"><?php echo esc_html($department->post_title); ?></span></li>
                    <?php endif; ?>
                    <?php if ($date_limit): ?>
                        <li><strong><?php echo esc_html__('Prazo:', 'info-acf-plugin'); ?></strong>
                            <span class="vacancy-chip"><?php echo esc_html($date_limit); ?></span></li>
                    <?php endif; ?>
                    <?php if ($reference): ?>
                        <li><strong><?php echo esc_html__('Ref.:', 'info-acf-plugin'); ?></strong>
                            <span class="vacancy-chip"><?php echo esc_html($reference); ?></span></li>
                    <?php endif; ?>
                </ul>

                <div class="ipp-vacancy-buttons">
                    <?php if ($url): ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank" class="btn-link green"><?php echo esc_html__('SABER MAIS', 'info-acf-plugin'); ?></a>
                    <?php endif; ?>
                    <?php if ($notice_url): ?>
                        <a href="<?php echo $notice_url; ?>" target="_blank" class="btn-link"><?php echo esc_html__('EDITAL', 'info-acf-plugin'); ?></a>
                    <?php endif; ?>
                    <a href="#" class="btn-link green vac-btn"
                       data-target="form-vacante"
                       data-vacancy="<?php echo esc_attr(get_the_ID()); ?>">
                       <?php echo esc_html__('APLICAR AGORA', 'info-acf-plugin'); ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php
    // Renderiza el popup SOLO una vez por página (si hay múltiples grids)
    static $ipp_vac_popup_printed = false;
    if (!$ipp_vac_popup_printed) :
        $ipp_vac_popup_printed = true; ?>
        <!-- Popup de Candidatura a Vaga -->
        <div id="form-vacante" class="vac-popup" aria-hidden="true" role="dialog">
          <div class="vac-popup-content">
            <button class="vac-close" aria-label="<?php echo esc_attr__('Fechar', 'info-acf-plugin'); ?>">&times;</button>
            <input type="hidden" id="selected-vacancy" name="vacancy_id" value="">
            <?php echo do_shortcode('[contact-form-7 id="'.esc_attr($atts['form']).'"]'); ?>
          </div>
        </div>
    <?php endif;

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('ipp_vacancies_grid', 'ipp_shortcode_vacancies_grid');
