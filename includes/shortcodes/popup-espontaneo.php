<?php
// [ipp_popup_espontaneo form="6f7a3f8" btn_text="Candidatura Espontânea" title="Candidatura Espontânea"]

function ipp_shortcode_popup_espontaneo($atts = []) {
    $atts = shortcode_atts([
        'form'     => '',
        'btn_text' => __('Candidatura Espontânea', 'info-acf-plugin'),
        'title'    => __('Candidatura Espontânea', 'info-acf-plugin'),
    ], $atts, 'ipp_popup_espontaneo');

    ob_start(); ?>

    <button class="vac-btn" data-target="form-espontaneo">
        <?php echo esc_html($atts['btn_text']); ?>
    </button>

    <?php
    // Renderiza el popup SOLO una vez por página
    static $ipp_esp_popup_printed = false;
    if (!$ipp_esp_popup_printed) :
        $ipp_esp_popup_printed = true; ?>
        <div id="form-espontaneo" class="vac-popup" aria-hidden="true" role="dialog">
          <div class="vac-popup-content">
            <button class="vac-close" aria-label="<?php echo esc_attr__('Fechar', 'info-acf-plugin'); ?>">&times;</button>
            <h2><?php echo esc_html($atts['title']); ?></h2>
            <?php
              if (!empty($atts['form'])) {
                echo do_shortcode('[contact-form-7 id="'.esc_attr($atts['form']).'"]');
              } else {
                echo '<p>'.esc_html__('Formulário não definido.', 'info-acf-plugin').'</p>';
              }
            ?>
          </div>
        </div>
    <?php endif;

    return ob_get_clean();
}
add_shortcode('ipp_popup_espontaneo', 'ipp_shortcode_popup_espontaneo');
