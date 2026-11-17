<?php
#db connection
include 'dbconn/db_connect.php';

#fetch customers
$customers = mysqli_query($conn, "SELECT * FROM customers");

#fetch products
$products = mysqli_query($conn, "SELECT * FROM products");

if(isset($_POST['submit'])){
     $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Get product details from database
    $product_sql =
    "SELECT * FROM products
    WHERE product_id = '$product_id'";

    $product_result =
    mysqli_query($conn,$product_sql);

    $product =
    mysqli_fetch_assoc($product_result);

    $price = $product['price'];

    $subtotal = $price * $quantity;

    /* Insert into Orders */

    $order_sql =
    "INSERT INTO orders
    (customer_id,total_amount,order_status)
    VALUES
    ('$customer_id','$subtotal','Pending')";

    mysqli_query($conn,$order_sql);

    $order_id = mysqli_insert_id($conn);

    /* Insert into Order Items */

    $item_sql =
    "INSERT INTO order_items
    (order_id,product_id,quantity,subtotal)
    VALUES
    ('$order_id',
     '$product_id',
     '$quantity',
     '$subtotal')";

    mysqli_query($conn,$item_sql);

    /* Update Stock */

    $stock_sql =
    "UPDATE products
    SET stock_quantity =
    stock_quantity - '$quantity'
    WHERE product_id = '$product_id'";

    mysqli_query($conn,$stock_sql);

    echo "
    <script>
        alert('Order Successfully Created');
        window.location='new_order.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raya Delights</title>
    <link rel="stylesheet" href="css/new_Order.css">
</head>
<body>
    <div class="container">
        <form action="" method="post" onsubmit="return confirm('Place this order?'); ">
        
        <h2>🎁 NEW ORDER 🎁</h2>

            <!-- Customer Fields -->
            <label>👤 Customer</label>
            <select name="customer_id" required>

            <option value="">--Select Customer--</option>
            
            <?php
            while($customerRow = mysqli_fetch_assoc($customers))
            {
            ?>

            <option value="<?php echo $customerRow['customer_id']; ?>">
                <?php echo $customerRow['customer_name']; ?>
            </option>

            <?php
            }
            ?>
            
            </select>

            <!-- Product Fields -->
            <label>🍪 Product</label>
            <select name="product_id" id="product" required>
            <option value="">--Select Product--</option>
                
            <?php
            while($productRow = mysqli_fetch_assoc($products)){
            ?>

            <option value="<?php echo $productRow['product_id']; ?>" 
            data-price="<?php echo $productRow['price']; ?>"
            data-stock="<?php echo $productRow['stock_quantity']; ?>">
                <?php echo $productRow['product_name']; ?>
            </option>

            <?php
            }
            ?>
            </select>

            <!-- Price Fields -->
            <label>💰 Price (RM)</label>
            <input type="text" id="price" name="price" readonly>

            <!-- Stock -->
            <label>📦 Stock Available</label>
            <input type="text" name="stock" id="stock" readonly>

            <!-- Quantity -->
            <label>🔢 Quantity</label>
            <input type="number" name="quantity" id="quantity" min="1" value="1" required>

            <!-- Total Amount -->
            <label>🧾 Total Amount (RM)</label>
            <input type="text" name="total" id="total" readonly>

            <br><br>
            <!-- Place Order -->
            <div class="button-group">
                <input type="submit" name="submit" value="Place Order">
                <input type="reset" value="Reset Form" id="resetBtn">
            </div>
        </form>

        <script>
            const product = document.getElementById("product");
            const price = document.getElementById("price");
            const stock = document.getElementById("stock");
            const quantity = document.getElementById("quantity");
            const total = document.getElementById("total");

            function calculateTotal(){
                let selectedOption = product.options[product.selectedIndex];

                let itemPrice = parseFloat(selectedOption.getAttribute("data-price"));

                let itemStock = selectedOption.getAttribute("data-stock");

                if(isNaN(itemPrice)){
                    price.value = "";
                    total.value = "";
                    return;
                }

                let qty = parseInt(quantity.value) || 0;
                stock.value = itemStock;
                quantity.max = itemStock;
                price.value = itemPrice.toFixed(2);
                total.value = (itemPrice * qty).toFixed(2);
            }

            product.addEventListener("change", calculateTotal);
            quantity.addEventListener("input", calculateTotal);
            quantity.addEventListener("input", function(){
                let selectedOption = product.options[product.selectedIndex];
                let stockQty = parseInt(selectedOption.getAttribute("data-stock"));

                if(parseInt(quantity.value) > stockQty){
                    alert("Quantity exceeds available stock!");
                    quantity.value = stockQty;
                }
            })
            calculateTotal();
        </script>
    </div>
</body>
</html>