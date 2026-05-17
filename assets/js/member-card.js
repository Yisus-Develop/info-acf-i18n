document.addEventListener('DOMContentLoaded', () => {
  // Portal: mover popups al <body> para evitar stacking contexts
  document.querySelectorAll('.mc-popup').forEach(p => {
    document.body.appendChild(p);
  });

  // Detección robusta de plataforma
  const ua = navigator.userAgent || '';
  const uaData = navigator.userAgentData || null;
  const isAndroid = /Android/i.test(ua) || (uaData && /Android/i.test(uaData.platform || ''));
  const isiOS     = /iPhone|iPad|iPod/i.test(ua);

  // Móvil: ajustar textos y aviso según plataforma
  document.querySelectorAll('.mc-mobile-actions').forEach(box => {
    const hint = box.querySelector('.mc-android-hint'); // lo usamos para ambos avisos
    const btn  = box.querySelector('.mc-download');
    const txt  = btn ? btn.querySelector('.mc-download-text') : null;
    if (!btn || !txt) return;

    const tSaveIOS  = (window.MCARD_I18N && MCARD_I18N.save_ios)     ? MCARD_I18N.save_ios     : 'Adicionar aos Contactos (iPhone)';
    const tSaveAND  = (window.MCARD_I18N && MCARD_I18N.save_android) ? MCARD_I18N.save_android : 'Guardar contacto (Android)';
    const tSaveVCF  = (window.MCARD_I18N && MCARD_I18N.save_vcf)     ? MCARD_I18N.save_vcf     : 'Guardar contacto (.vcf)';
    const tHintIOS  = (window.MCARD_I18N && MCARD_I18N.hint_ios)     ? MCARD_I18N.hint_ios     : 'Ao tocar, abrirá nos Contactos.';
    const tHintAND  = (window.MCARD_I18N && MCARD_I18N.hint_android) ? MCARD_I18N.hint_android : 'Será descarregado o ficheiro .vcf; abra-o para adicionar aos seus contactos.';

    if (isiOS) {
      txt.textContent = tSaveIOS;
      if (hint) { hint.hidden = false; hint.textContent = tHintIOS; }
    } else if (isAndroid) {
      txt.textContent = tSaveAND;
      if (hint) { hint.hidden = false; hint.textContent = tHintAND; }
    } else {
      txt.textContent = tSaveVCF;
      if (hint) { hint.hidden = true; }
    }
  });

  // Desktop: toggle de QR (una sola imagen, cambiamos el src)
  document.querySelectorAll('.mc-qrcenter').forEach(center => {
    const img  = center.querySelector('.mc-qr');
    const cap  = center.querySelector('.mc-qr-caption');
    const tabs = center.querySelectorAll('.mc-qr-tab');
    if (!img || !tabs.length) return;

    const labelVCF = (window.MCARD_I18N && MCARD_I18N.cap_vcf)    ? MCARD_I18N.cap_vcf    : 'Escaneie com iPhone';
    const labelMEC = (window.MCARD_I18N && MCARD_I18N.cap_mecard) ? MCARD_I18N.cap_mecard : 'Escaneie com Android';

    const srcVCF = img.dataset.srcVcf || '';
    const srcMEC = img.dataset.srcMecard || '';

    const activate = (target) => {
      tabs.forEach(t => {
        const isTarget = t.getAttribute('data-target') === target;
        t.classList.toggle('is-active', isTarget);
        t.setAttribute('aria-selected', isTarget ? 'true' : 'false');
      });
      if (target === 'mecard' && srcMEC) {
        img.src = srcMEC;
        if (cap) cap.textContent = labelMEC;
      } else if (srcVCF) {
        img.src = srcVCF;
        if (cap) cap.textContent = labelVCF;
      }
    };

    if (isAndroid && srcMEC) { activate('mecard'); }
    else if (srcVCF)         { activate('vcf');    }

    center.addEventListener('click', (e) => {
      const tab = e.target.closest('.mc-qr-tab');
      if (!tab) return;
      e.preventDefault();
      activate(tab.getAttribute('data-target'));
    });
  });
});

// Abrir / cerrar modal (delegación global)
document.addEventListener('click', (e) => {
  const open = e.target.closest('.mc-open');
  if (open){
    e.preventDefault();
    const id = open.getAttribute('data-target');
    const p  = document.getElementById(id);
    if (p) { p.classList.add('active'); p.setAttribute('aria-hidden','false'); }
  }
  const close = e.target.closest('.mc-close');
  if (close){
    const pop = close.closest('.mc-popup');
    if (pop) { pop.classList.remove('active'); pop.setAttribute('aria-hidden','true'); }
  }
  if (e.target.classList.contains('mc-popup')){
    e.target.classList.remove('active');
    e.target.setAttribute('aria-hidden','true');
  }
});
