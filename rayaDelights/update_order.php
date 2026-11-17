<?php
include 'dbconn/db_connect.php';

if(isset($_POST['update']))
{
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $update_sql = "
    UPDATE orders
    SET order_status = '$order_status'
    WHERE order_id = '$order_id'
    ";

    mysqli_query($conn, $update_sql);

    echo "
    <script>
        alert('Order status updated successfully!');
        window.location='update_order.php';
    </script>
    ";
}

$sql = 
"SELECT 
orders.order_id,
customers.customer_name,
orders.order_date,
orders.total_amount,
orders.order_status
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.customer_id
ORDER BY orders.order_id ASC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raya Delights</title>
    <link rel="stylesheet" href="css/update_Order.css">
</head>
<body>
    <div class="container">
        <div class="orderTable">
            <h1>📦 ORDER MANAGEMENT 📦</h1>

            <table>
                <tr>
                    <th>ORDER ID</th>
                    <th>CUSTOMER</th>
                    <th>ORDER DATE</th>
                    <th>TOTAL AMOUNT</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>

                <?php
                while($row = mysqli_fetch_assoc($result))
                    {
                ?>

                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td>RM <?php echo number_format($row['total_amount'], 2); ?></td>
                    <td>
                        <?php
                        $status = $row['order_status'];

                        if($status == "Pending"){
                            echo "<span class='pending'>$status</span>";
                        }
                        elseif($status == "Processing"){
                            echo "<span class='processing'>$status</span>";
                        }
                        elseif($status == "Completed"){
                            echo "<span class='completed'>$status</span>";
                        }
                        else{
                            echo "<span class='cancelled'>$status</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="order_id"
                            value="<?php echo $row['order_id']; ?>">

                            <select name="order_status">

                                <option value="Pending"
                                <?php if($row['order_status']=="Pending") echo "selected"; ?>>
                                Pending
                                </option>

                                <option value="Processing"
                                <?php if($row['order_status']=="Processing") echo "selected"; ?>>
                                Processing
                                </option>

                                <option value="Completed"
                                <?php if($row['order_status']=="Completed") echo "selected"; ?>>
                                Completed
                                </option>

                                <option value="Cancelled"
                                <?php if($row['order_status']=="Cancelled") echo "selected"; ?>>
                                Cancelled
                                </option>

                            </select>

                            <input type="submit" name="update" value="UPDATE" onclick="return confirm('Update order status?'); ">
                        </form>
                    </td>
                </tr>

                <?php
                    }
                ?>
            </table>
        </div>
    </div>
</body>
</html>