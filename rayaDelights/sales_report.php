<?php
include 'dbconn/db_connect.php';

/* Total Revenue */
$sqlRevenue = 
"SELECT SUM(total_amount) AS total_revenue
FROM orders WHERE order_status = 'Completed'
";

$revenueResult = mysqli_query($conn, $sqlRevenue);
$revenueRow = mysqli_fetch_assoc($revenueResult);

/* Total Orders */
$sqlOrders = "SELECT COUNT(*) AS total_orders FROM orders";

$orderResult = mysqli_query($conn, $sqlOrders);
$orderRow = mysqli_fetch_assoc($orderResult);

/* Best Selling Product */
$sqlBest = 
"SELECT
products.product_name,
SUM(order_items.quantity) AS total_sold
FROM order_items
INNER JOIN products
ON order_items.product_id = products.product_id
GROUP BY products.product_id, products.product_name
ORDER BY total_sold DESC
LIMIT 1
";

$bestResult = mysqli_query($conn, $sqlBest);
$bestRow = mysqli_fetch_assoc($bestResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raya Delights</title>
    <link rel="stylesheet" href="css/sales_Report.css">
</head>
<body>
    <div class="container">
        <h1>📊 RAYA DELIGHTS SALES REPORT 📊</h1>

        <div class="report-card">
            <h2>Total Revenue</h2>
            <p>
                RM <?php echo number_format($revenueRow['total_revenue'] ?? 0, 2); ?>
            </p>
        </div>

        <div class="report-card">
            <h2>Total Orders</h2>
            <p>
                <?php echo $orderRow['total_orders']; ?>
            </p>
        </div>

        <div class="report-card">
            <h2>Best Selling Product</h2>
            <p>
                <?php echo $bestRow['product_name'] ?? 'No Sales Yet'; ?>
            </p>
            <small>
                Total Sold:
                <?php echo $bestRow['total_sold'] ?? 0;?>
                unit(s)
            </small>
        </div>
    </div>
</body>
</html>