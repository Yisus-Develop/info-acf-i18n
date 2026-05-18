<?php
// Shortcode: [pipeline_digital_pipeline]
// Renderiza el pipeline DIGITAL (solo la función, sin encolar CSS/JS aún)

add_shortcode('pipeline_digital_pipeline', function () {
    $pipelines = get_posts([
        'post_type'      => 'pipeline',
        'posts_per_page' => -1,
        'meta_key'       => 'pipeline_type',
        'meta_value'     => 'digital',
         'orderby'        => 'date',  // <--- ORDEN POR FECHA
    'order'          => 'DESC'   // <--- Más reciente arriba
        
        
    ]);
    ob_start(); ?>
    <div class="pipeline-digital-list">
        <div class="pipeline-digital-header">
            <div><?php _e('Product', 'eweb-content-functionalities'); ?></div>
            <div><?php _e('Target', 'eweb-content-functionalities'); ?></div>
            <div><?php _e('Market', 'eweb-content-functionalities'); ?></div>
            <div><?php _e('Development', 'eweb-content-functionalities'); ?></div>
            <div><?php _e('Market Launch', 'eweb-content-functionalities'); ?></div>
            <div><?php _e('Comments', 'eweb-content-functionalities'); ?></div>
        </div>
        <?php foreach ($pipelines as $post):
            $id  = $post->ID;
            $dg  = get_field('digital', $id);
            if (!$dg) continue;
            // Icono de la taxonomía (opcional)
            $icon_html = '';
            if (!empty($dg['pipeline_icon'])) {
                $img = get_field('icon_image', 'pipeline_icon_' . $dg['pipeline_icon']);
                $url = is_array($img) && !empty($img['url'])
                    ? $img['url']
                    : (is_numeric($img) ? wp_get_attachment_image_url($img, 'thumbnail') : '');
                if ($url) {
                    $icon_html = '<img src="'.esc_url($url).'" class="pipeline-digital-icon" alt="" />';
                }
            }
            // Barras binarias (0 o 100)
            $dev_width = !empty($dg['development']) ? 100 : 0;
            $ml_width  = !empty($dg['market_launch']) ? 100 : 0;
        ?>
        <div class="pipeline-digital-row">
            <!-- Product -->
            <div class="cell cell-product">
                <div class="cell-mini-label"><?php _e('Product', 'eweb-content-functionalities'); ?></div>
                <div class="cell-product-flex">
                    <?php echo $icon_html; ?>
                    <span class="cell-product-value">
                        <?php echo esc_html($dg['product'] ?? ''); ?>
                    </span>
                </div>
            </div>
            <!-- Target -->
            <div class="cell cell-target">
                <div class="cell-mini-label"><?php _e('Target', 'eweb-content-functionalities'); ?></div>
                <div class="cell-value-scroll"><?php echo esc_html($dg['target'] ?? ''); ?></div>
            </div>
            <!-- Market -->
            <div class="cell cell-market">
                <div class="cell-mini-label"><?php _e('Market', 'eweb-content-functionalities'); ?></div>
                <div class="cell-value-scroll"><?php echo esc_html($dg['market'] ?? ''); ?></div>
            </div>
            <!-- Development (binary bar) -->
            <div class="cell cell-development">
                <div class="cell-mini-label"><?php _e('Development', 'eweb-content-functionalities'); ?></div>
                <div class="pipeline-bar">
                    <div class="pipeline-bar-fill" data-width="<?php echo esc_attr($dev_width); ?>"></div>
                </div>
            </div>
            <!-- Market Launch (binary bar) -->
            <div class="cell cell-launch">
                <div class="cell-mini-label"><?php _e('Market Launch', 'eweb-content-functionalities'); ?></div>
                <div class="pipeline-bar">
                    <div class="pipeline-bar-fill patent" data-width="<?php echo esc_attr($ml_width); ?>"></div>
                </div>
            </div>
            <!-- Comments -->
            <div class="cell cell-comments">
                <div class="cell-mini-label"><?php _e('Comments', 'eweb-content-functionalities'); ?></div>
                <div class="cell-comments-ellipsis">
                    <?php echo esc_html($dg['comments'] ?? ''); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});
