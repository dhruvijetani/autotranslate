document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('start-translate-btn');
    const loader = document.getElementById('translation-overlay');

    if (btn) {
        btn.addEventListener('click', function(e) {
            // Show Loader Overlay
            loader.style.display = 'flex';
            
            // Prevent multiple clicks by adding locking class
            btn.classList.add('btn-locked');
            btn.innerHTML = '⚙️ Translating...';
            
            // The link will continue to the controller automatically
        });
    }
});