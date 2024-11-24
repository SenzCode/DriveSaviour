<?php
    require '../navbar/navbar.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <!----<h2 class="comhead">Commission by Day</h2>--->
    <div class="content">
        <!-- Month Selection Dropdown -->
        <label for="month">Select Month:</label>
        <select id="month" onchange="updateChart()">
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11" selected>November</option>
            <option value="12">December</option>
        </select>
        
        <!-- Chart Canvas -->
        <canvas id="commissionChart"></canvas>
    </div>

    <script>
        let chart; // Store the chart instance

        // Automatically set the dropdown to the current month
        document.getElementById('month').value = new Date().getMonth() + 1;

        async function updateChart() {
            const month = document.getElementById('month').value;
            const year = new Date().getFullYear();

            try {
                const response = await fetch(`fetchcommission.php?month=${month}`);
                const data = await response.json();

                if (!data || data.error) {
                    console.error('API Error:', data.error || 'Empty response');
                    alert(data.error || 'No data available for this month.');
                    return;
                }

                const daysInMonth = new Date(year, month, 0).getDate();
                const completeDates = Array.from({ length: daysInMonth }, (_, i) => {
                    const day = i + 1;
                    return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                });

                const commissionData = completeDates.map(date => {
                    const record = data.find(row => row.order_date === date);
                    return record ? parseFloat(record.total_commission || 0) : 0;
                });

                if (chart) {
                    chart.data.labels = completeDates;
                    chart.data.datasets[0].data = commissionData;
                    chart.update();
                } else {
                    const ctx = document.getElementById('commissionChart').getContext('2d');
                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: completeDates,
                            datasets: [
                                {
                                    label: 'Total Commission',
                                    data: commissionData,
                                    pointRadius: 6,
                                    borderColor: 'green',
                                    backgroundColor: 'rgba(144, 238, 144, 0.5)',
                                    borderWidth: 4,
                                    tension: 0.1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            const value = context.raw;
                                            const date = context.label;
                                            return `Date: ${date}\nCommission: Rs. ${value}`;
                                        }
                                    }
                                },
                                legend: {
                                    display: true
                                }
                            },
                            scales: {
                                x: {
                                    title: { display: true, text: 'Date' },
                                    ticks: { maxTicksLimit: 4 }
                                },
                                y: {
                                    title: { display: true, text: 'Commission (Rs)' },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error fetching chart data:', error);
                alert('Failed to load chart data.');
            }
        }

        // Automatically load the current month's data on page load
        document.addEventListener('DOMContentLoaded', updateChart);
    </script>
</body>
</html>
