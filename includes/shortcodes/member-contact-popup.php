<?php
defined('ABSPATH') || exit;

// [member_contact_popup id="" btn="Cartão Digital"]
add_shortcode('member_contact_popup', function($atts){
  global $post;

  $default_btn = __('Cartão Digital', 'info-acf-plugin');
  $a = shortcode_atts(['id'=>'','btn'=>$default_btn], $atts, 'member_contact_popup');

  // Resolver ID
  $id = intval($a['id']);
  if (!$id && isset($post->ID)) $id = intval($post->ID);
  if (!$id) return '';

  $name = get_the_title($id);
  if(!$name) return '';

  // Datos ACF
  $function = get_field('function',$id);
  $degree   = get_field('degree',$id);
  $email    = get_field('email',$id);
  $phone    = get_field('phone',$id);
  $wa       = get_field('whatsapp',$id);
  $site     = get_field('website',$id);
  $linkedin = get_field('linkedin',$id);
  $maps     = get_field('location_url',$id);
  $bio      = get_field('bio',$id);

  // vCard + QR
  $slug     = sanitize_title($name);
  $hash     = substr(md5($id . SECURE_AUTH_SALT), 0, 4);
  $filebase = $slug.'-'.$hash;
  $vcf_url  = content_url('/vcards/'.$filebase.'.vcf');
  $qr_vcf   = content_url('/vcards/'.$filebase.'.png');
  $qr_mec   = content_url('/vcards/'.$filebase.'-mecard.png');

  $photo    = get_the_post_thumbnail_url($id,'large');

  // Evitar duplicados del mismo popup por página
  static $printed = [];
  $uid = 'mc-'.$id;

  ob_start();

  if (isset($printed[$uid])) {
    echo '<button class="mc-open" data-target="'.esc_attr($uid).'"><span class="mc-open__icon" aria-hidden="true">📇</span><span>'.esc_html($a['btn']).'</span></button>';
    return ob_get_clean();
  }
  $printed[$uid] = true;
  ?>
  <div class="mc-wrap">
    <button class="mc-open" data-target="<?php echo esc_attr($uid); ?>">
      <span class="mc-open__icon" aria-hidden="true">📇</span>
      <span><?php echo esc_html($a['btn']); ?></span>
    </button>

    <div id="<?php echo esc_attr($uid); ?>" class="mc-popup" aria-hidden="true">
      <div class="mc-card" role="dialog" aria-modal="true" aria-labelledby="mc-title-<?php echo esc_attr($id); ?>">

        <button class="mc-close" aria-label="<?php echo esc_attr__('Fechar', 'info-acf-plugin'); ?>">
          <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>

        <div class="mc-hero">
          <?php if($photo): ?>
            <img class="mc-photo" src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($name); ?>">
          <?php else: ?>
            <div class="mc-photo mc-photo--placeholder" aria-hidden="true"><?php echo strtoupper(mb_substr($name,0,1)); ?></div>
          <?php endif; ?>
          <div class="mc-id">
            <h3 id="mc-title-<?php echo esc_attr($id); ?>" class="mc-name"><?php echo esc_html($name); ?></h3>
            <p class="mc-role"><?php echo esc_html(trim(($function?:'').($degree?(' – '.$degree):''))); ?></p>
            <?php if($bio): ?><p class="mc-bio"><?php echo esc_html($bio); ?></p><?php endif; ?>
          </div>
        </div>

        <div class="mc-grid">
          <?php if($phone):   ?><a class="mc-chip" href="tel:<?php echo esc_attr($phone); ?>"><span class="mc-ico"><?php echo mc_svg('phone'); ?></span> <?php echo esc_html__('Chamada', 'info-acf-plugin'); ?></a><?php endif; ?>
          <?php if($wa):      ?><a class="mc-chip" target="_blank" rel="noopener" href="<?php echo esc_url( preg_match('~^https?://~',$wa)?$wa:'https://wa.me/'.preg_replace('/\D+/','',$wa) ); ?>"><span class="mc-ico"><?php echo mc_svg('whatsapp'); ?></span> <?php echo esc_html__('Mensagem', 'info-acf-plugin'); ?></a><?php endif; ?>
          <?php if($email):   ?><a class="mc-chip" href="mailto:<?php echo esc_attr($email); ?>"><span class="mc-ico"><?php echo mc_svg('mail'); ?></span> <?php echo esc_html__('E-mail', 'info-acf-plugin'); ?></a><?php endif; ?>
          <?php if($linkedin):?><a class="mc-chip" target="_blank" rel="noopener" href="<?php echo esc_url($linkedin); ?>"><span class="mc-ico"><?php echo mc_svg('linkedin'); ?></span> <?php echo esc_html__('LinkedIn', 'info-acf-plugin'); ?></a><?php endif; ?>
          <?php if($site):    ?><a class="mc-chip" target="_blank" rel="noopener" href="<?php echo esc_url($site); ?>"><span class="mc-ico"><?php echo mc_svg('globe'); ?></span> <?php echo esc_html__('Website', 'info-acf-plugin'); ?></a><?php endif; ?>
          <?php if($maps):    ?><a class="mc-chip" target="_blank" rel="noopener" href="<?php echo esc_url($maps); ?>"><span class="mc-ico"><?php echo mc_svg('map'); ?></span> <?php echo esc_html__('Localização', 'info-acf-plugin'); ?></a><?php endif; ?>
        </div>

        <?php
          $has_vcf = @getimagesize($qr_vcf) ? true : false;
          $has_mec = @getimagesize($qr_mec) ? true : false;
        ?>
        <div class="mc-footer">
          <!-- Móvil: botón -->
          <div class="mc-mobile-actions">
            <a class="mc-download" href="<?php echo esc_url($vcf_url); ?>">
              <span class="mc-ico"><?php echo mc_svg('download'); ?></span>
              <span class="mc-download-text"><?php echo esc_html__('Guardar contacto (.vcf)', 'info-acf-plugin'); ?></span>
            </a>
            <small class="mc-android-hint" hidden><?php echo esc_html__('Será descarregado o ficheiro .vcf; abra-o para adicionar aos seus contactos.', 'info-acf-plugin'); ?></small>
          </div>
 <small class="mc-qr-tip">Escolha o seu dispositivo.<br> Se o QR não abrir contactos, use o botão no telemóvel.</small>
          <!-- Desktop: QR con tabs -->
          <?php if ($has_vcf || $has_mec): ?>
          <div class="mc-qrcenter">
            <div class="mc-qr-toggle" role="tablist" aria-label="<?php echo esc_attr__('Formato de QR', 'info-acf-plugin'); ?>">
              <?php if ($has_vcf): ?>
               <button class="mc-qr-tab is-active" data-target="vcf" role="tab" aria-selected="true">
  <?php echo esc_html__( 'iPhone', 'info-acf-plugin' ); ?>
</button>
              <?php endif; ?>
              <?php if ($has_mec): ?>
                <button class="mc-qr-tab ..." data-target="mecard" ...>
  <?php echo esc_html__( 'Android', 'info-acf-plugin' ); ?>
</button>
              <?php endif; ?>
            </div>
            
           


            <figure class="mc-qrbox">
              <img class="mc-qr"
                   src="<?php echo esc_url($has_vcf ? $qr_vcf : ($has_mec ? $qr_mec : '')); ?>"
                   data-src-vcf="<?php echo esc_attr($has_vcf ? $qr_vcf : ''); ?>"
                   data-src-mecard="<?php echo esc_attr($has_mec ? $qr_mec : ''); ?>"
                   alt="<?php echo esc_attr__('QR contacto', 'info-acf-plugin'); ?>">
              <figcaption class="mc-qr-caption">
                <?php echo esc_html( $has_vcf ? __('QR (.vcf)', 'info-acf-plugin') : __('QR MECARD', 'info-acf-plugin') ); ?>
              </figcaption>
            </figure>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
});

/** Íconos SVG inline */
if (!function_exists('mc_svg')) {
  function mc_svg($name){
    $icons = [
      'phone' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24 11.36 11.36 0 003.56.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h2.49a1 1 0 011 1 11.36 11.36 0 00.57 3.56 1 1 0 01-.24 1.01l-2.2 2.2z"/></svg>',
      'whatsapp' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20.52 3.48A11.9 11.9 0 0012.05 0 12 12 0 000 12a11.93 11.93 0 001.76 6.25L0 24l5.93-1.55A12 12 0 0012 24h.05A12 12 0 0024 12a11.9 11.9 0 00-3.48-8.52zM12 22a10 10 0 01-5.1-1.4l-.37-.22-3.52.92.94-3.43-.24-.35A9.94 9.94 0 012 12a10 10 0 1010 10zm5.44-7.56c-.3-.15-1.77-.87-2.04-.97s-.47-.15-.67.15-.77.97-.94 1.17-.35.22-.65.07a8.1 8.1 0 01-2.38-1.47 9 9 0 01-1.67-2.07c-.17-.3 0-.46.13-.61l.33-.39c.15-.17.2-.29.3-.49s0-.37-.05-.52c-.07-.15-.67-1.6-.92-2.18s-.49-.5-.67-.51h-.57a1.1 1.1 0 00-.8.37 3.37 3.37 0 00-1 2.5 5.84 5.84 0 001.22 3.1c.14.19 1.92 2.93 4.64 4.1a15.83 15.83 0 001.57.58 3.77 3.77 0 001.73.11c.53-.08 1.77-.72 2-1.41s.24-1.29.17-1.41-.27-.2-.57-.35z"/></svg>',
      'mail' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5L4 8V6l8 5 8-5v2z"/></svg>',
      'linkedin' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V24h-4V8zm7.5 0h3.8v2.2h.05C12.49 8.9 14.24 8 16.5 8 21 8 22 10.9 22 15.2V24h-4v-7.3c0-1.74-.03-3.98-2.43-3.98-2.43 0-2.8 1.9-2.8 3.86V24h-4V8z"/></svg>',
      'globe' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm7.93 9h-3.05a16.7 16.7 0 00-.88-4.01A8.02 8.02 0 0119.93 11zM12 4a13.9 13.9 0 011.7 5H10.3A13.9 13.9 0 0112 4zM4.07 13h3.05c.18 1.39.53 2.74 1.03 4.01A8.02 8.02 0 014.07 13zM8.15 11H4.07a8.02 8.02 0 013.08-4.01A16.7 16.7 0 008.15 11zM10.3 13h3.4A13.9 13.9 0 0112 20a13.9 13.9 0 01-1.7-7zM15.8 13h3.12a8.02 8.02 0 01-3.06 4.03c.5-1.28.83-2.64.94-4.03z"/></svg>',
      'map' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20.5 3l-5.5 2-6-2-5.5 2v15l5.5-2 6 2 5.5-2V3zm-11 2.18l4 1.33v12.31l-4-1.33V5.18zM5 6.09l2-0.73v12.31l-2 .73V6.09zM19 17.91l-2 .73V6.33l2-.73v12.31z"/></svg>',
      'download' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M5 20h14v-2H5v2zm7-18l-5 5h3v6h4V7h3l-5-5z"/></svg>',
    ];
    return $icons[$name] ?? '';
  }
}
