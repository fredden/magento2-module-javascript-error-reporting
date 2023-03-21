/* global define */

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
                        display: false,
                    },
                    title: {
                        display: true,
                        text: config.title,
                    },
                },
                scales: {
                    x: {
                        display: false,
                    },
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    };
});
