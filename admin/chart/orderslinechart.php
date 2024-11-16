<?php
    require '../navbar/navbar.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <h1>Orders by Day</h1>
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
        <canvas id="ordersChart"></canvas>
    </div>

    <script>
        let chart; // Store the chart instance

        async function updateChart() {
            const month = document.getElementById('month').value; // Get selected month
            const year = new Date().getFullYear(); // Get the current year

            try {
                const response = await fetch(`fetch_orders.php?month=${month}`); // Fetch data for the selected month
                const data = await response.json();

                // Check for errors in the response
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Generate a complete date range for the month
                const daysInMonth = new Date(year, month, 0).getDate();
                const completeDates = Array.from({ length: daysInMonth }, (_, i) => {
                    const day = i + 1;
                    return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                });

                // Map the fetched data to the complete date range, filling in 0 for missing dates
                const orderData = completeDates.map(date => {
                    const record = data.find(row => row.order_date === date);
                    return record ? record.order_count : 0;
                });

                // If the chart already exists, update it
                if (chart) {
                    chart.data.labels = completeDates;
                    chart.data.datasets[0].data = orderData;
                    chart.update();
                } else {
                    // Create the chart
                    const ctx = document.getElementById('ordersChart').getContext('2d');
                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: completeDates,
                            datasets: [{
                                label: 'Number of Orders',
                                data: orderData,
                                pointRadius: 6,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(173, 216, 230, 0.5)',
                                borderWidth: 4,
                                tension: 0.1 // Smooth lines
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    enabled: true,
                                    callbacks: {
                                        label: function (context) {
                                            const value = context.raw;
                                            const date = context.label;
                                            return `Date: ${date}\nOrders: ${value}`;
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
                                    ticks: {
                                        maxTicksLimit: 4, // Show only 4 evenly spaced ticks
                                        callback: function (value, index, values) {
                                            // Format the labels to show only dates
                                            return completeDates[value];
                                        }
                                    }
                                },
                                y: {
                                    title: { display: true, text: 'Orders' },
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1, // Ensure step size is 1
                                        callback: function (value) {
                                            if (Number.isInteger(value)) {
                                                return value; // Show only integer values
                                            }
                                            return null; // Skip non-integer values
                                        }
                                    }
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

        // Fetch and display data for the default month on page load
        document.addEventListener('DOMContentLoaded', updateChart);

    </script>
</body>
</html>
