document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('start-translate-btn');
    const loader = document.getElementById('translation-overlay');
    if (btn) {
        btn.addEventListener('click', function(e) {
            loader.style.display = 'flex';
            btn.classList.add('btn-locked');
            btn.innerHTML = 'Translating...';
         });
    }
});