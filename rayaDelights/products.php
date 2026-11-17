<?php
include "dbconn/db_connect.php";

//Fetch products
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raya Delights</title>
    <link rel="stylesheet" href="css/product.css">
</head>
<body>
    <div class="productTable">
        <h1>🍪 RAYA DELIGHTS PRODUCTS 🍪</h1>

        <table>
        <tr>
            <th>PRODUCT ID</th>
            <th>PRODUCT_NAME</th>
            <th>CATEGORY</th>
            <th>PRICE</th>
            <th>STOCK_QUANTITY</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['product_id'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['stock_quantity'] . "</td>";
            echo "</tr>";
        }
        ?>
        </table>
    </div>
</body>
</html>