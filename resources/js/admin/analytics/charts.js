const initCharts = {
    revenue: function(chartType = 'area') {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const colors = {
            text: isDarkMode ? '#e5e7eb' : '#374151',
            grid: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
            line: isDarkMode ? '#34d399' : '#059669',
            fill: isDarkMode ? 'rgba(52, 211, 153, 0.1)' : 'rgba(5, 150, 105, 0.1)',
            bar: isDarkMode ? '#34d399' : '#059669'
        };

        return {
            chart: {
                type: chartType,
                height: 320,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            theme: {
                mode: isDarkMode ? 'dark' : 'light'
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: [colors.line],
            fill: {
                type: 'gradient',
                gradient: {
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    stops: [0, 100],
                    colorStops: [
                        {
                            offset: 0,
                            color: colors.fill,
                            opacity: 1
                        },
                        {
                            offset: 100,
                            color: colors.fill,
                            opacity: 0
                        }
                    ]
                }
            },
            grid: {
                borderColor: colors.grid,
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                labels: {
                    style: {
                        colors: colors.text
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: colors.text
                    }
                }
            },
            tooltip: {
                theme: isDarkMode ? 'dark' : 'light'
            }
        };
    },

    categories: function() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const colors = {
            text: isDarkMode ? '#e5e7eb' : '#374151',
            grid: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
            bars: isDarkMode ? 
                ['#34d399', '#60a5fa', '#a78bfa', '#f59e0b', '#ef4444'] :
                ['#059669', '#3b82f6', '#7c3aed', '#d97706', '#dc2626']
        };

        return {
            chart: {
                type: 'bar',
                height: 320,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            theme: {
                mode: isDarkMode ? 'dark' : 'light'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: colors.bars,
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return new Intl.NumberFormat('ru-RU').format(val) + ' ₽';
                },
                style: {
                    colors: [colors.text]
                }
            },
            grid: {
                borderColor: colors.grid,
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                labels: {
                    style: {
                        colors: colors.text
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: colors.text
                    }
                }
            },
            tooltip: {
                theme: isDarkMode ? 'dark' : 'light'
            }
        };
    }
};

export default initCharts;