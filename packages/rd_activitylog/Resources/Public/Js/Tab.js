document.addEventListener('DOMContentLoaded', function () {
    const tabs = {
        insights: { btn: 'btn-insights', content: 'tab-insights', init: initOSCharts },
        backend: { btn: 'btn-backend', content: 'tab-backend', init: null },
        eco: { btn: 'btn-eco', content: 'tab-eco', init: initGlobalChart },
        activitylog: { btn: 'btn-activitylog', content: 'tab-activitylog', init: null } 
    };

    function switchTab(activeType) {
        Object.keys(tabs).forEach(type => {
            const isMatch = type === activeType;
            const element = document.getElementById(tabs[type].content);
            const button = document.getElementById(tabs[type].btn);

            if (element) element.style.display = isMatch ? 'block' : 'none';
            if (button) button.classList.toggle('active', isMatch);
            if (isMatch && tabs[type].init) {
                setTimeout(tabs[type].init, 150);
            }
        });
    }

    Object.keys(tabs).forEach(type => {
        document.getElementById(tabs[type].btn)?.addEventListener('click', () => switchTab(type));
    });

    switchTab('insights');
});
let globalChartInstance = null;
function initGlobalChart() {
    const canvas = document.getElementById('globalPayloadChart');
    if (!canvas) return;

    const rawData = JSON.parse(canvas.dataset.payload);
    const labels = Object.keys(rawData);
    const dataValues = labels.map(l => rawData[l].mb);
    const colors = labels.map(l => rawData[l].color);
    
    const total = dataValues.reduce((a, b) => a + b, 0).toFixed(2);
    document.querySelector('.total-mb-display').innerText = total + ' MB';

    if (window.myGlobalChart) window.myGlobalChart.destroy();

    window.myGlobalChart = new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            hover: {
                mode: null 
            },
            animation: {
                animateRotate: true,
                render: false 
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    intersect: true
                }
            }
        }
    });
}
let osChartsInitialized = false;
function initOSCharts() {

    if (osChartsInitialized) {
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded');
        return;
    }

    document.querySelectorAll('.os-chart').forEach(canvas => {

        const raw = canvas.dataset.os;
        if (!raw) {
            return;
        }

        const osData = JSON.parse(raw);
        const labels = Object.keys(osData);
        const values = Object.values(osData);

        if (!labels.length) {
            return;
        }

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });

    osChartsInitialized = true;
}
document.addEventListener('DOMContentLoaded', function() {
    const flushBtn = document.getElementById('flush-log-btn');
    const modalOverlay = document.getElementById('custom-alert-overlay');
    const cancelBtn = document.getElementById('alert-cancel');
    const confirmBtn = document.getElementById('alert-confirm');

    if (flushBtn && modalOverlay) {
        flushBtn.addEventListener('click', function(event) {
            event.preventDefault(); 
            modalOverlay.style.display = 'flex'; 
        });

        cancelBtn.addEventListener('click', function() {
            modalOverlay.style.display = 'none'; 
        });

        confirmBtn.addEventListener('click', function() {
            const targetUrl = flushBtn.getAttribute('href');
            window.location.href = targetUrl; 
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.btn-filter');
    const rows = document.querySelectorAll('.log-row');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const range = this.dataset.range;
            const now = Math.floor(Date.now() / 1000); 
            let cutoff = 0;

            if (range === 'today') {
                const startOfToday = new Date();
                startOfToday.setHours(0, 0, 0, 0);
                cutoff = Math.floor(startOfToday.getTime() / 1000);
            } else if (range === '7') {
                cutoff = now - (7 * 24 * 60 * 60);
            } else if (range === '30') {
                cutoff = now - (30 * 24 * 60 * 60);
            } else {
                cutoff = 0; 
            }
            rows.forEach(row => {
                const rowTime = parseInt(row.getAttribute('data-tstamp'));

                if (range === 'all' || rowTime >= cutoff) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        });
    });
});















