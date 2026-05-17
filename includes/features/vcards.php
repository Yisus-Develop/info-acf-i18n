<?php
defined('ABSPATH') || exit;

// Cambia a false si no quieres embutir la foto en base64 dentro del .vcf
if (!defined('IAP_VCARD_EMBED_PHOTO')) {
  define('IAP_VCARD_EMBED_PHOTO', true);
}

/** Helper folding línea vCard */
if (!function_exists('iap_vcard_fold_line')) {
  function iap_vcard_fold_line($line) {
    $out = '';
    $max = 75;
    while (mb_strlen($line, '8bit') > $max) {
      $out .= mb_substr($line, 0, $max, '8bit') . "\r\n" . ' ';
      $line = mb_substr($line, $max, null, '8bit');
    }
    $out .= $line;
    return $out;
  }
}

/** Generar vCard 3.0 al guardar miembro */
add_action('save_post_iplantprotect_member', function($post_id, $post, $update){
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;

  // Carpeta /wp-content/vcards/
  $dir = WP_CONTENT_DIR . '/vcards/';
  if (!file_exists($dir)) wp_mkdir_p($dir);

  // Nombre de archivo: slug + hash corto (no expone ID)
  $slug     = sanitize_title(get_the_title($post_id));
  $hash     = substr(md5($post_id . SECURE_AUTH_SALT), 0, 4);
  $filename = $slug . '-' . $hash;
  $file     = $dir . $filename . '.vcf';

  // Helpers
  $esc = function($v){ $v=(string)$v; return str_replace(["\\","\r\n","\n",",",";"],["\\\\","\\n","\\n","\\,","\\;"],$v); };
  $norm_phone = function($v){
    $v = trim((string)$v); if ($v==='') return '';
    $v = preg_replace('/[^0-9+]/', '', $v);
    $v = preg_replace('/\+(?=.)/', '', $v, -1);
    if ($v !== '' && $v[0] !== '+') $v = '+' . $v;
    return $v;
  };
  $ensure_url = function($u){ $u=trim((string)$u); if($u==='') return ''; if(!preg_match('~^https?://~i',$u)) $u='https://'.$u; return $u; };
  $split_name = function($full){
    $full = trim((string)$full); if ($full==='') return ['','','','',''];
    $parts = preg_split('/\s+/', $full);
    $first = array_shift($parts); $last = implode(' ', $parts);
    return [$last, $first, '', '', '']; // N: Apellido;Nombre;;;
  };

  // Datos
  $name      = get_the_title($post_id);
  $function  = get_field('function', $post_id);
  $degree    = get_field('degree',   $post_id);
  $email     = get_field('email',    $post_id);
  $phone     = $norm_phone(get_field('phone',    $post_id));
  $whatsapp  = $norm_phone(get_field('whatsapp', $post_id));
  $website   = $ensure_url(get_field('website',  $post_id));
  $linkedin  = $ensure_url(get_field('linkedin', $post_id));
  $location  = $ensure_url(get_field('location_url', $post_id));
  $bio       = get_field('bio', $post_id);
  $org       = 'InnovPlantProtect';

  $profile_url = get_permalink($post_id);
  $photo_url   = get_the_post_thumbnail_url($post_id, 'full');

  // Foto embebida opcional
  $photo_line = '';
  $thumb_id   = get_post_thumbnail_id($post_id);
  if (IAP_VCARD_EMBED_PHOTO && $thumb_id) {
    $src_path = get_attached_file($thumb_id);
    if ($src_path && file_exists($src_path)) {
      $editor = wp_get_image_editor($src_path);
      if (!is_wp_error($editor)) {
        $editor->resize(400, 400, false);
        $tmp_path = WP_CONTENT_DIR . '/vcards/tmp-' . $post_id . '.jpg';
        $saved    = $editor->save($tmp_path, 'image/jpeg');
        if (!is_wp_error($saved) && file_exists($tmp_path)) {
          $bin = file_get_contents($tmp_path);
          if ($bin !== false) {
            $b64 = base64_encode($bin);
            $photo_line = iap_vcard_fold_line('PHOTO;ENCODING=b;TYPE=JPEG:' . $b64);
          }
          @unlink($tmp_path);
        }
      }
    }
  }

  // Build vCard
  $lines = [];
  $lines[] = 'BEGIN:VCARD';
  $lines[] = 'VERSION:3.0';
  [$n_last,$n_first,$n3,$n4,$n5] = $split_name($name);
  $lines[] = 'N:'  . $esc($n_last) . ';' . $esc($n_first) . ';' . $esc($n3) . ';' . $esc($n4) . ';' . $esc($n5);
  $lines[] = 'FN:' . $esc($name);
  if ($org)      $lines[] = 'ORG:'   . $esc($org);
  if ($function) $lines[] = 'TITLE:' . $esc($function);
  if ($degree)   $lines[] = 'ROLE:'  . $esc($degree);
  if ($email)    $lines[] = 'EMAIL;TYPE=INTERNET,WORK:' . $esc($email);
  if ($phone)    $lines[] = 'TEL;TYPE=WORK,VOICE:'      . $esc($phone);
  if ($whatsapp) { $lines[]='item1.TEL:' . $esc($whatsapp); $lines[]='item1.X-ABLabel:WhatsApp'; }
  if ($profile_url){ $lines[]='URL:' . $esc($profile_url); $lines[]='item2.URL:' . $esc($profile_url); $lines[]='item2.X-ABLabel:Perfil InPP'; }
  if ($website)    { $lines[]='URL:' . $esc($website);     $lines[]='item3.URL:' . $esc($website);     $lines[]='item3.X-ABLabel:Website'; }
  if ($linkedin)   { $lines[]='item4.URL:' . $esc($linkedin); $lines[]='item4.X-ABLabel:LinkedIn'; $lines[]='X-LINKEDIN:' . $esc($linkedin); }
  if ($location)   { $lines[]='item5.URL:' . $esc($location); $lines[]='item5.X-ABLabel:Localização'; }

  // Foto
  if ($photo_line) {
    $lines[] = $photo_line; // ya plegada
  } elseif ($photo_url) {
    $lines[] = 'PHOTO;VALUE=URI:' . $esc($photo_url);
  }

  if ($bio) { $lines[] = 'NOTE:' . $esc($bio); }
  $lines[] = 'UID:' . $filename;
  $lines[] = 'REV:' . gmdate('Ymd\THis\Z');
  $lines[] = 'END:VCARD';

  $vcard = implode("\r\n", $lines) . "\r\n";
  file_put_contents($file, $vcard);

}, 10, 3);

/** Generar QR que apunta a la URL del .vcf */
add_action('save_post_iplantprotect_member', function($post_id, $post, $update){
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;

  $dir = WP_CONTENT_DIR . '/vcards/';
  if (!file_exists($dir)) wp_mkdir_p($dir);

  $slug     = sanitize_title(get_the_title($post_id));
  $hash     = substr(md5($post_id . SECURE_AUTH_SALT), 0, 4);
  $filename = $slug . '-' . $hash;

  $vcf_path = $dir . $filename . '.vcf';
  if (!file_exists($vcf_path)) return;

  $vcf_url = content_url('/vcards/' . $filename . '.vcf');
  $qr_path = $dir . $filename . '.png';

  $qr_api = add_query_arg([
    'size' => '512x512',
    'data' => $vcf_url,
  ], 'https://api.qrserver.com/v1/create-qr-code/');

  $res = wp_remote_get($qr_api, ['timeout' => 15]);
  if (!is_wp_error($res) && wp_remote_retrieve_response_code($res) === 200) {
    $png = wp_remote_retrieve_body($res);
    if ($png) file_put_contents($qr_path, $png);
  }
}, 30, 3);



/** Helper: escapar texto para MECARD */
if (!function_exists('iap_mecard_escape')) {
  function iap_mecard_escape($s){
    $s = (string)$s;
    return str_replace(
      ['\\','; ',',',':',"\r","\n"],
      ['\\\\','\;','\,','\:',' ',' '],
      trim($s)
    );
  }
}

/** Generar QR MECARD (rápido en Android) */
add_action('save_post_iplantprotect_member', function($post_id, $post, $update){
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;

  $dir = WP_CONTENT_DIR . '/vcards/';
  if (!file_exists($dir)) wp_mkdir_p($dir);

  $name = get_the_title($post_id);
  $slug = sanitize_title($name);
  $hash = substr(md5($post_id . SECURE_AUTH_SALT), 0, 4);
  $filebase = $slug.'-'.$hash;

  // Datos mínimos para MECARD
  $function = get_field('function', $post_id);
  $degree   = get_field('degree',   $post_id);
  $email    = get_field('email',    $post_id);
  $phone    = get_field('phone',    $post_id);
  $wa       = get_field('whatsapp', $post_id);
  $website  = get_field('website',  $post_id);
  $org      = 'InnovPlantProtect';

  // Nombre "Apellido,Nombre" simple
  $parts = preg_split('/\s+/', trim($name));
  $first = array_shift($parts);
  $last  = implode(' ', $parts);
  $N     = ($last && $first) ? ($last . ',' . $first) : $name;

  $title = $function ? $function . ($degree ? ' — '.$degree : '') : ($degree ?: '');

  $mecard = 'MECARD:';
  $mecard .= 'N:'    . iap_mecard_escape($N) . ';';
  if ($org)      $mecard .= 'ORG:'   . iap_mecard_escape($org) . ';';
  if ($title)    $mecard .= 'TITLE:' . iap_mecard_escape($title) . ';';
  if ($phone)    $mecard .= 'TEL:'   . iap_mecard_escape($phone) . ';';
  if ($wa)       $mecard .= 'TEL:'   . iap_mecard_escape($wa) . ';'; // lector lo tratará como otro teléfono
  if ($email)    $mecard .= 'EMAIL:' . iap_mecard_escape($email) . ';';
  if ($website)  $mecard .= 'URL:'   . iap_mecard_escape($website) . ';';
  $mecard .= ';'; // cierre

  $qr_api = add_query_arg([
    'size' => '512x512',
    'data' => $mecard,
  ], 'https://api.qrserver.com/v1/create-qr-code/');

  $res = wp_remote_get($qr_api, ['timeout' => 15]);
  if (!is_wp_error($res) && wp_remote_retrieve_response_code($res) === 200) {
    $png = wp_remote_retrieve_body($res);
    if ($png) file_put_contents($dir . $filebase . '-mecard.png', $png);
  }
}, 35, 3);
