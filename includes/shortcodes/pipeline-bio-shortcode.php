<?php
/**
 * Shortcode: [pipeline_bio_pipeline]
 * Este shortcode renderiza el pipeline BIO y carga solo el CSS y JS necesarios.
 */


// Shortcode: [pipeline_bio_pipeline]
add_shortcode('pipeline_bio_pipeline', function () {
    $pipelines = get_posts([
        'post_type'      => 'pipeline',
        'posts_per_page' => -1,
        'meta_key'       => 'pipeline_type',
        'meta_value'     => 'bio',
         'orderby'        => 'date',  // <--- ORDEN POR FECHA
    'order'          => 'DESC'   // <--- Más reciente arriba
    ]);


    ob_start(); ?>
    <div class="pipeline-bio-pipeline-list">
        <div class="pipeline-bio-pipeline-header">
            <div><?php _e('Development Code', 'info-acf-plugin');?></div>
            <div><?php _e('Target', 'info-acf-plugin');?></div>
            <div><?php _e('Market', 'info-acf-plugin');?></div>
            <div><?php _e('Development', 'info-acf-plugin');?></div>
            <div><?php _e('Patent filling', 'info-acf-plugin');?></div>
            <div><?php _e('Comments', 'info-acf-plugin');?></div>
        </div>
        <?php foreach ($pipelines as $post):
            $id  = $post->ID;
            $bio = get_field('bio', $id);
            if (!$bio) continue;
            // Icono de la taxonomía (opcional)
            $icon_html = '';
            if (!empty($bio['pipeline_icon'])) {
                $img = get_field('icon_image', 'pipeline_icon_' . $bio['pipeline_icon']);
                $url = is_array($img) && !empty($img['url'])
                    ? $img['url']
                    : (is_numeric($img) ? wp_get_attachment_image_url($img, 'thumbnail') : '');
                if ($url) {
                    $icon_html = '<img src="'.esc_url($url).'" class="pipeline-bio-icon" alt="" />';
                }
            }
            // Link del Development Code si hay proyecto relacionado
            $project_id  = !empty($bio['project_relation']) ? $bio['project_relation'] : 0;
            $project_url = $project_id ? get_permalink($project_id) : '';
            // Progreso (0–3 pasos => 0%, 33.33%, 66.66%, 100%)
            $devs = [
                !empty($bio['development']['in_vitro']),
                !empty($bio['development']['in_planta']),
                !empty($bio['development']['field'])
            ];
            $last_dev = 0; for ($i=0; $i<3; $i++) if ($devs[$i]) $last_dev = $i+1;
            $dev_w = number_format($last_dev * 33.3333, 2, '.', '');
            $patents = [
                !empty($bio['patent_filling']['ppp']),
                !empty($bio['patent_filling']['pct']),
                !empty($bio['patent_filling']['national_phases'])
            ];
            $last_pat = 0; for ($i=0; $i<3; $i++) if ($patents[$i]) $last_pat = $i+1;
            $pat_w = number_format($last_pat * 33.3333, 2, '.', '');
        ?>
        <div class="pipeline-bio-pipeline-row">
            <!-- Development Code -->
            <div class="cell cell-code">
                <div class="cell-mini-label"><?php _e('Development Code', 'info-acf-plugin');?></div>
                <div class="cell-code-flex">
                    <?php echo $icon_html; ?>
                    <span class="cell-code-value">
                        <?php if ($project_url): ?>
                            <a class="cell-code-link" href="<?php echo esc_url($project_url); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html($bio['development_code'] ?? ''); ?>
                            </a>
                        <?php else: ?>
                            <?php echo esc_html($bio['development_code'] ?? ''); ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <!-- Target -->
            <div class="cell cell-target">
                <div class="cell-mini-label"><?php _e('Target', 'info-acf-plugin');?></div>
                <div class="cell-value-scroll"><?php echo esc_html($bio['target'] ?? ''); ?></div>
            </div>
            <!-- Market -->
            <div class="cell cell-market">
                <div class="cell-mini-label"><?php _e('Market', 'info-acf-plugin');?></div>
                <div class="cell-value-scroll"><?php echo esc_html($bio['market'] ?? ''); ?></div>
            </div>
            <!-- Development -->
            <div class="cell cell-development">
                <div class="cell-mini-label"><?php _e('Development', 'info-acf-plugin');?></div>
                <div class="bar-labels">
                    <span><?php _e('In-Vitro','info-acf-plugin');?></span>
                    <span><?php _e('In-Planta','info-acf-plugin');?></span>
                    <span><?php _e('Field','info-acf-plugin');?></span>
                </div>
                <div class="pipeline-bar">
                    <div class="pipeline-bar-fill" data-width="<?php echo $dev_w; ?>"></div>
                </div>
            </div>
            <!-- Patent filling -->
            <div class="cell cell-patent">
                <div class="cell-mini-label"><?php _e('Patent filling', 'info-acf-plugin');?></div>
                <div class="bar-labels">
                    <span><?php _e('PPP','info-acf-plugin');?></span>
                    <span><?php _e('PCT','info-acf-plugin');?></span>
                    <span><?php _e('National phases','info-acf-plugin');?></span>
                </div>
                <div class="pipeline-bar">
                    <div class="pipeline-bar-fill patent" data-width="<?php echo $pat_w; ?>"></div>
                </div>
            </div>
            <!-- Comments -->
            <div class="cell cell-comments">
                <div class="cell-mini-label"><?php _e('Comments', 'info-acf-plugin');?></div>
                <div class="cell-comments-ellipsis"><?php echo esc_html($bio['comments'] ?? ''); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});
