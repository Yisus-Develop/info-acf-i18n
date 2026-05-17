// Animación de barras (pipeline-bio/digital)
(function(){
    document.addEventListener('DOMContentLoaded', function () {
        var bars = document.querySelectorAll('.pipeline-bar-fill');
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function(entries){
                entries.forEach(function(entry){
                    if (!entry.isIntersecting) return;
                    var bar = entry.target;
                    if (bar.dataset.animated === '1') return;
                    var pct = parseFloat(bar.getAttribute('data-width')) || 0;
                    bar.style.width = '0%';
                    setTimeout(function(){ bar.style.width = pct + '%'; }, 50);
                    bar.dataset.animated = '1';
                    io.unobserve(bar);
                });
            }, { threshold: 0.15 });
            bars.forEach(function(b){ io.observe(b); });
        } else {
            // Fallback simple (viejos browsers)
            function animateFallback(){
                bars.forEach(function(bar){
                    if (bar.dataset.animated === '1') return;
                    var rect = bar.getBoundingClientRect();
                    if (rect.top < window.innerHeight - 60) {
                        var pct = parseFloat(bar.getAttribute('data-width')) || 0;
                        bar.style.width = '0%';
                        setTimeout(function(){ bar.style.width = pct + '%'; }, 50);
                        bar.dataset.animated = '1';
                    }
                });
            }
            animateFallback();
            window.addEventListener('scroll', animateFallback, { passive: true });
        }
    });
})();
