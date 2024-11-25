<?php
require '../navbar/nav.php';
require '../../connection.php';

$loggedInEmail = $_SESSION['email'];

// Get the current date and the date for 7 days ago
$currentDate = date('Y-m-d');
$lastWeekDate = date('Y-m-d', strtotime('-1 week'));

// Fetch top 10 trending products of the week with quantities from both tables
$trendingQuery = "
    SELECT p.id, p.product_name, p.image_url, 
           SUM(o.quantity) AS total_sold
    FROM products p
    INNER JOIN shops s ON p.shop_id = s.id
    LEFT JOIN (
        SELECT product_id, SUM(quantity) AS quantity 
        FROM orders 
        WHERE purchase_date BETWEEN '$lastWeekDate' AND '$currentDate' 
        GROUP BY product_id
        UNION ALL
        SELECT product_id, SUM(quantity) AS quantity 
        FROM mech_orders 
        WHERE purchase_date BETWEEN '$lastWeekDate' AND '$currentDate' 
        GROUP BY product_id
    ) o ON p.id = o.product_id
    WHERE s.ownerEmail = '$loggedInEmail'
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 10";

$trendingResult = mysqli_query($conn, $trendingQuery);
$trendingProducts = [];
if ($trendingResult) {
    while ($row = mysqli_fetch_assoc($trendingResult)) {
        $trendingProducts[] = $row;
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending Products Bar Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #trendingBarChart {
            width: 100%;
            height: 400px;
        }

        .chart-container {
            text-align: center;
        }

        .no-data {
            font-size: 1.2rem;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="chart-container">
    <h2>Top 10 Trending Products of the Week</h2>
    <?php if (!empty($trendingProducts)): ?>
        <canvas id="trendingBarChart"></canvas>
    <?php else: ?>
        <p class="no-data">No trending products found for the selected period.</p>
    <?php endif; ?>
</div>

<?php if (!empty($trendingProducts)): ?>
<script>
    // Prepare data for the chart
    const productNames = <?= json_encode(array_column($trendingProducts, 'product_name')) ?>;
    const productSales = <?= json_encode(array_map(function ($product) {
        return (int) $product['total_sold'];
    }, $trendingProducts)) ?>;
    const productImages = <?= json_encode(array_column($trendingProducts, 'image_url')) ?>;

    const ctx = document.getElementById('trendingBarChart').getContext('2d');

    // Create the bar chart
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Total Sold',
                data: productSales,
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Total Sold: ${tooltipItem.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: { beginAtZero: true },
                y: { beginAtZero: true }
            }
        },
        plugins: [{
            id: 'barImages',
            afterDatasetDraw(chart) {
                const { ctx } = chart;
                chart.getDatasetMeta(0).data.forEach((bar, index) => {
                    const img = new Image();
                    img.src = productImages[index] || 'default.png';
                    img.onload = () => {
                        // Define the image dimensions
                        const imageWidth = 70;
                        const imageHeight = 70;
                        const xPos = bar.x - imageWidth / 2;
                        const yPos = bar.y - imageHeight - 10;

                        // Draw the frame (border)
                        const framePadding = 5;
                        ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';
                        ctx.lineWidth = 2;
                        ctx.strokeRect(
                            xPos - framePadding, 
                            yPos - framePadding, 
                            imageWidth + framePadding * 2, 
                            imageHeight + framePadding * 2
                        );

                        // Draw the image
                        ctx.drawImage(img, xPos, yPos, imageWidth, imageHeight);
                    };
                });
            }
        }]


    });
</script>
<?php endif; ?>
</body>
</html>
