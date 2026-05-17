/**
 * JS para popups de candidaturas (espontáneo y vacantes)
 */
document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.vac-btn');
  const popups  = document.querySelectorAll('.vac-popup');

  buttons.forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const targetId  = btn.getAttribute('data-target');
      const vacancyId = btn.getAttribute('data-vacancy') || '';

      // cerrar popups abiertos
      popups.forEach(p => p.classList.remove('active'));

      // abrir el destino
      const popup = document.getElementById(targetId);
      if (popup) {
        popup.classList.add('active');
        popup.setAttribute('aria-hidden', 'false');
      }

      // setear vacante (si aplica)
      if (vacancyId) {
        const hidden = document.getElementById('selected-vacancy');
        if (hidden) hidden.value = vacancyId;

        const selectVac = document.querySelector('#ipp-cf7-vacancy');
        if (selectVac) selectVac.value = vacancyId;
      }
    });
  });

  // cerrar al hacer click en fondo o botón de cierre
  document.body.addEventListener('click', e => {
    if (e.target.matches('.vac-close') || e.target.classList.contains('vac-popup')) {
      const popup = e.target.closest('.vac-popup') || e.target;
      popup.classList.remove('active');
      popup.setAttribute('aria-hidden', 'true');
    }
  });
});
