var map;
if ($('#vmap').length) {
    function initMap() {
        map = new google.maps.Map(document.getElementById('vmap'), {
            center: {
                lat: -34.397,
                lng: 150.644
            },
            zoom: 100
        });
    };
}
(function($) {
    'use strict';
    $(function() {
        var orderByLocationData = {
            datasets: [{
                data: [45, 20, 25, 10],
                backgroundColor: [
                    '#5d78ff',
                    '#ffb61b',
                    '#2cd9c5',
                    '#fc4a9e',
                ],
                borderColor: [
                    '#5d78ff',
                    '#ffb61b',
                    '#2cd9c5',
                    '#fc4a9e',
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Saint Lucia',
                'Liberia',
                'Saint Helena',
                'Kenya',
            ]
        };
        var orderByLocationOptions = {
            responsive: true,
            legend: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };
        if ($("#order-by-location").length) {
            var pieChartCanvas = $("#order-by-location").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: orderByLocationData,
                options: orderByLocationOptions
            });
        }
        var saleByLocationData = {
            datasets: [{
                data: [45, 20, 25, 10],
                backgroundColor: [
                    '#fc3995',
                    '#5d78ff',
                    '#ffb720',
                    '#40c4ff',
                ],
                borderColor: [
                    '#fc3995',
                    '#5d78ff',
                    '#ffb720',
                    '#40c4ff',
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Saint Lucia',
                'Liberia',
                'Saint Helena',
                'Kenya',
            ]
        };
        var saleByLocationOptions = {
            responsive: true,
            legend: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };
        if ($("#sale-by-location").length) {
            var pieChartCanvas = $("#sale-by-location").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: saleByLocationData,
                options: saleByLocationOptions
            });
        }
        var revenueData = {
            labels: ["2005", "2010", "2015", "2020", "2025", "2030", "2035", "2040", "2045", "2050"],
            datasets: [{
                label: '# of Votes',
                data: [50, 40, 55, 35, 45, 55, 40, 50, 45, 50],
                backgroundColor: [
                    '#e0f5f2',
                ],
                borderColor: [
                    '#2cd9c5',
                ],
                borderWidth: 3,
                fill: true, // 3: no fill
                pointBorderWidth: 3,
                pointRadius: [0, 0, 0, 0, 0, 6, 0, 0, 0, 0],
                pointHoverRadius: [0, 0, 0, 0, 6, 0, 0, 0, 0, 0],
                pointBackgroundColor: ['#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2'],
                pointBorderColor: ['#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5'],
            }]
        };

        var revenueOptions = {
            plugins: {
                filler: {
                    propagate: true
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: "rgba(90, 113, 208, 0.4",
                    },
                    gridLines: {
                        color: "rgba(90, 113, 208, 0.11)",
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: "rgba(0, 0, 0, 0)",
                    },
                    ticks: {
                        beginAtZero: true,
                        fontColor: "rgba(90, 113, 208, 0.4",
                    },
                }]
            },
            legend: {
                display: false
            }
        }

        if ($("#revenueChart").length) {
            var areaChartCanvas = $("#revenueChart").get(0).getContext("2d");
            var areaChart = new Chart(areaChartCanvas, {
                type: 'line',
                data: revenueData,
                options: revenueOptions
            });
        }

        var revenueDarkData = {
            labels: ["2005", "2010", "2015", "2020", "2025", "2030", "2035", "2040", "2045", "2050"],
            datasets: [{
                label: '# of Votes',
                data: [50, 40, 55, 35, 45, 55, 40, 50, 45, 50],
                backgroundColor: [
                    'rgba(41,44,60, .7)',
                ],
                borderColor: [
                    '#2cd9c5',
                ],
                borderWidth: 3,
                fill: true, // 3: no fill
                pointBorderWidth: 3,
                pointRadius: [0, 0, 0, 0, 0, 5, 0, 0, 0, 0],
                pointHoverRadius: [0, 0, 0, 0, 5, 0, 0, 0, 0, 0],
                pointBackgroundColor: ['#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2', '#e0f5f2'],
                pointBorderColor: ['#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5', '#2cd9c5'],
            }]
        };

        var revenueDarkOptions = {
            plugins: {
                filler: {
                    propagate: true
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: "#292c3c",
                    },
                    gridLines: {
                        color: "#1f233c",
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: "rgba(0, 0, 0, 0)",
                    },
                    ticks: {
                        beginAtZero: true,
                        fontColor: "#292c3c",
                    },
                }]
            },
            legend: {
                display: false
            }
        }

        if ($("#revenueChart-dark").length) {
            var areaChartCanvas = $("#revenueChart-dark").get(0).getContext("2d");
            var areaChart = new Chart(areaChartCanvas, {
                type: 'line',
                data: revenueDarkData,
                options: revenueDarkOptions
            });
        }

        $.fn.andSelf = function() {
            return this.addBack.apply(this, arguments);
        }
        if ($('.detailed-report').length) {
            $('.detailed-report').owlCarousel({
                loop: false,
                margin: 10,
                items: 1,
                nav: true,
                autoplay: false,
                dots: false,
                autoplayTimeout: 5500,
                navText: ["<i class='ion ion-ios-arrow-back'></i>", "<i class='ion ion-ios-arrow-forward'></i>"]
            });
        }
        var orderData = {
            labels: ["10", "20", "30", "40", "50", "60", "70"],
            datasets: [{
                label: 'Orders',
                data: [26, 16, 13, 38, 27, 19, 23],
                backgroundColor: [
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                ],
                borderColor: [
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                ],
                borderWidth: 2,
                fill: false
            },
            ],
        };
        var orderOptions = {
            scales: {
                xAxes: [{
                    position: 'bottom',
                    display: true,
                    gridLines: {
                        display: false,
                        drawBorder: true,
                    },
                    ticks: {
                        display: true, //this will remove only the label
                        autoSkip: false,
                        maxRotation: 1,
                        minRotation: 1,
                        fontSize: 10
                    }
                }],
                yAxes: [{
                    display: false,
                    gridLines: {
                        drawBorder: true,
                        display: false,
                    },
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }
        };

        if ($("#order").length) {
            var barChartCanvas = $("#order").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: orderData,
                options: orderOptions
            });
        }
        if ($("#orderSlider").length) {
            var barChartCanvas = $("#orderSlider").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: orderData,
                options: orderOptions
            });
        }
        var usersData = {
            labels: ["10", "20", "30", "40", "50", "60", "70"],
            datasets: [{
                label: 'Orders',
                data: [26, 16, 13, 25, 17, 19, 23],
                backgroundColor: [
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                ],
                borderColor: [
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                    '#5d78ff',
                ],
                borderWidth: 2,
                fill: false
            },
            ],
        };
        var usersOptions = {
            scales: {
                xAxes: [{
                    position: 'bottom',
                    display: true,
                    gridLines: {
                        display: false,
                        drawBorder: true,
                    },
                    ticks: {
                        display: true, //this will remove only the label
                        autoSkip: false,
                        maxRotation: 1,
                        minRotation: 1,
                        fontSize: 10
                    }
                }],
                yAxes: [{
                    display: false,
                    gridLines: {
                        drawBorder: true,
                        display: false,
                    },
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }
        };

        if ($("#users").length) {
            var barChartCanvas = $("#users").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: usersData,
                options: usersOptions
            });
        }
        if ($("#usersSlider").length) {
            var barChartCanvas = $("#usersSlider").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: usersData,
                options: usersOptions
            });
        }

        var stateBySaleDarkData = {
            labels: ["2am", "4am", "5am", "6am", "7am", "8am", "9am"],
            datasets: [{
                label: 'Impression',
                data: [8, 10, 8, 9, 4, 5, 5],
                backgroundColor: [
                    'rgba(113,115,123,.5)',
                    'rgba(113,115,123,.5)',
                    'rgba(113,115,123,.5)',
                    'rgba(113,115,123,.5)',
                    'rgba(113,115,123,.5)',
                    'rgba(113,115,123,.5)',
                    'rgba(113,115,123,.5)',
                ],
                borderColor: [
                    'rgba(113,115,123,0)',
                    'rgba(113,115,123,0)',
                    'rgba(113,115,123,0)',
                    'rgba(113,115,123,0)',
                    'rgba(113,115,123,0)',
                    'rgba(113,115,123,0)',
                    'rgba(113,115,123,0)',
                ],
                borderWidth: 2,
                fill: false
            },
                {
                    label: 'Turnover',
                    data: [7, 6, 6, 8, 7, 7, 7],
                    backgroundColor: [
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                    ],
                    borderColor: [
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                    ],
                    borderWidth: 2,
                    fill: false
                }
            ],
        };
        var stateBySaleDarkOptions = {
            scales: {
                xAxes: [{
                    position: 'bottom',
                    display: true,
                    gridLines: {
                        display: false,
                        drawBorder: true,

                    },
                    ticks: {
                        display: true, //this will remove only the label
                        fontColor: "#292c3c",
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        drawBorder: true,
                        display: true,
                        color: "#202326"
                    },
                    ticks: {
                        display: true, //this will remove only the label
                        beginAtZero: true,
                        fontColor: "#292c3c",
                    }
                }]
            },
            legend: {
                display: false
            },
            legendCallback: function(chart) {
                var text = [];
                text.push('<div class="row">');
                for (var i = 0; i < chart.data.datasets.length; i++) {
                    text.push('<div class="col-sm-6 col mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0"><div class="row mb-3 align-items-center"><div class="col-md-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-sm-12"><p class="font-weight-medium mr-3">' + chart.data.datasets[i].label + '</p></div></div>');
                    text.push('</div>');
                }
                text.push('</div>');
                return text.join("");
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }

        };

        if ($("#state-by-sale-dark").length) {
            var barChartCanvas = $("#state-by-sale-dark").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: stateBySaleDarkData,
                options: stateBySaleDarkOptions,
            });
            document.getElementById('chart-legends-state-by-sale').innerHTML = barChart.generateLegend();
        }
        var stateBySaleData = {
            labels: ["2am", "4am", "5am", "6am", "7am", "8am", "9am"],
            datasets: [{
                label: 'Impression',
                data: [8, 10, 8, 9, 4, 5, 5],
                backgroundColor: [
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                ],
                borderColor: [
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                    '#e1e6ff',
                ],
                borderWidth: 2,
                fill: false
            },
                {
                    label: 'Turnover',
                    data: [7, 6, 6, 8, 7, 7, 7],
                    backgroundColor: [
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                    ],
                    borderColor: [
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                        '#5d78ff',
                    ],
                    borderWidth: 2,
                    fill: false
                }
            ],
        };
        var stateBySaleOptions = {
            scales: {
                xAxes: [{
                    position: 'bottom',
                    display: true,
                    gridLines: {
                        display: false,
                        drawBorder: true,

                    },
                    ticks: {
                        display: true, //this will remove only the label
                        fontColor :"rgba(90, 113, 208, 0.4)",
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        drawBorder: true,
                        display: true,
                        color: "rgba(90, 113, 208, 0.11)"
                    },
                    ticks: {
                        display: true, //this will remove only the label
                        beginAtZero: true,
                        stepSize: 3,
                        fontColor :"rgba(90, 113, 208, 0.4)",
                    }
                }]
            },
            legend: {
                display: false
            },
            legendCallback: function(chart) {
                var text = [];
                text.push('<div class="row">');
                for (var i = 0; i < chart.data.datasets.length; i++) {
                    text.push('<div class="col-sm-6 col mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0"><div class="row mb-3 align-items-center"><div class="col-md-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-sm-12"><p class="text-dark font-weight-medium mr-3">' + chart.data.datasets[i].label + '</p></div></div>');
                    text.push('</div>');
                }
                text.push('</div>');
                return text.join("");
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }

        };

        if ($("#state-by-sale").length) {
            var barChartCanvas = $("#state-by-sale").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: stateBySaleData,
                options: stateBySaleOptions,
            });
            document.getElementById('chart-legends-state-by-sale').innerHTML = barChart.generateLegend();
        }
        // Region Charts Ends
        var barChartStackedData = {
            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"],
            datasets: [{
                label: 'Safari',
                data: [15, 20, 25, 30, 25, 20, 15, 20, 25, 30, 25, 20, 15, 10, 15, 20],
                backgroundColor: [
                    '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff',

                ],
                borderColor: [
                    '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff',
                ],
                borderWidth: 1,
                fill: false
            },
                {
                    label: 'Chrome',
                    data: [15, 20, 25, 30, 25, 20, 15, 20, 25, 30, 25, 20, 15, 10, 15, 20],

                    backgroundColor: [
                        '#d8d8d8', '#d8d8d8', '#d8d8d8', 'd8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8',
                    ],
                    borderColor: [
                        '#d8d8d8', '#d8d8d8', '#d8d8d8', 'd8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8', '#d8d8d8',

                    ],
                    borderWidth: 1,
                    fill: false
                }
            ]
        };
        var barChartStackedOptions = {
            scales: {
                xAxes: [{
                    display: false,
                    stacked: true,
                    gridLines: {
                        display: false //this will remove only the label
                    },
                }],
                yAxes: [{
                    stacked: true,
                    display: false,
                }]
            },
            legend: {
                display: false,
                position: "bottom"
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        };
        if ($("#barChartStacked").length) {
            var barChartCanvas = $("#barChartStacked").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartStackedData,
                options: barChartStackedOptions
            });
        }

        var barChartStackedDarkData = {
            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"],
            datasets: [{
                label: 'Safari',
                data: [15, 20, 25, 30, 25, 20, 15, 20, 25, 30, 25, 20, 15, 10, 15, 20],
                backgroundColor: [
                    '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff',

                ],
                borderColor: [
                    '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff', '#5d78ff',
                ],
                borderWidth: 1,
                fill: false
            },
                {
                    label: 'Chrome',
                    data: [15, 20, 25, 30, 25, 20, 15, 20, 25, 30, 25, 20, 15, 10, 15, 20],

                    backgroundColor: [
                        'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', '71737b', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)', 'rgba(113,115,123,.5)',
                    ],
                    borderColor: [
                        'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', '71737b', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0))', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)', 'rgba(113,115,123,0)',

                    ],
                    borderWidth: 1,
                    fill: false
                }
            ]
        };
        var barChartStackedDarkOptions = {
            scales: {
                xAxes: [{
                    display: false,
                    stacked: true,
                    gridLines: {
                        display: false //this will remove only the label
                    },
                }],
                yAxes: [{
                    stacked: true,
                    display: false,
                }]
            },
            legend: {
                display: false,
                position: "bottom"
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        };
        if ($("#barChartStackedDark").length) {
            var barChartCanvas = $("#barChartStackedDark").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartStackedDarkData,
                options: barChartStackedDarkOptions
            });
        }
        if ($("#vmap").length) {
            $('#vmap').vectorMap({
                map: 'world_mill_en',
                panOnDrag: true,
                backgroundColor: "fff",
                focusOn: {
                    x: 0.5,
                    y: 0.5,
                    scale: 1,
                    animate: true,
                },
                series: {
                    regions: [{
                        normalizeFunction: 'polynomial',
                        values: {}
                    }]
                },
                regionStyle: {
                    initial: {
                        fill: '#d2d6de'
                    },
                    hover: {
                        fill: '#A0D1DC'
                    }
                },
            });
        }
        if ($("#vmap-dark").length) {
            $('#vmap-dark').vectorMap({
                map: 'world_mill_en',
                panOnDrag: true,
                backgroundColor: "#181b2e",
                focusOn: {
                    x: 0.5,
                    y: 0.5,
                    scale: 1,
                    animate: true,
                },
                series: {
                    regions: [{
                        normalizeFunction: 'polynomial',
                        values: {}
                    }]
                },
                regionStyle: {
                    initial: {
                        fill: '#d2d6de'
                    },
                    hover: {
                        fill: '#A0D1DC'
                    }
                },
            });
        }


    });

})(jQuery);