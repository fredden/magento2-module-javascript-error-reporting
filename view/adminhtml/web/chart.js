/*global define */
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
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: config.title,
                },
                legend: {
                    onClick: function () {}, // disable toggle
                    position: 'bottom',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                    }]
                }
            }
        });
    };
});
