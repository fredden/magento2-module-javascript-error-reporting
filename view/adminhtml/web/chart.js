/* global define, requirejs */

// This isn't nice. Reconfigure the path to chartJs to use a CDN as fall-back
// because chartJs is not available by default in Magento v2.3 (but is in v2.4)
requirejs.config({
    paths: {
        chartJs: [
            'chartjs/Chart.min',
            'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min',
        ],
    }
});

define([
    'chartJs',
], function (Chart) {
    'use strict';

    return function (config, element) {
        new Chart(element, {
            type: 'line',
            data: {
                labels: config.data.labels,
                datasets: [{
                    label: '# of Errors',
                    data: config.data.values,
                    // These colours are from Magento_Backend::js/dashboard/chart.js
                    backgroundColor: '#f1d4b3',
                    borderColor: '#eb5202',
                    borderWidth: 1,
                    // Preserve styles from Charts.js v2.9.3
                    cubicInterpolationMode: 'monotone',
                    fill: true,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        onClick: function () {}, // disable toggle
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: config.title,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    };
});
