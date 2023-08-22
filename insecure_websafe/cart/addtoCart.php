<?php
session_start();
if (!isset($_SESSION["user_id"])){
    header("Location: /");
}

$con = mysqli_connect("insecure_database", "Lottie", "Ad0r@ble", "websafe");

if (!$con) {
    die("Failed to connect " . mysqli_connect_errno());
}

$userID = $_SESSION['user_id'];
$productID = $_GET['product_id'];
$quantity = 1;

$checkresult = $con->prepare("SELECT * FROM user_cart WHERE cartproduct_id = ? and cart_userid = ?");
$checkresult->bind_param('ii', $productID, $userID);
$checkresult->execute();
$checkresult->store_result();

if ($checkresult->num_rows == 0) {
    $query = "SELECT * FROM products WHERE product_id = ?";
    $pQuery = $con->prepare($query);
    $pQuery->bind_param('i', $productID);
    $pQuery->execute();
    $result = $pQuery->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $prodName = $row['name'];

        $insertQuery = $con->prepare("INSERT INTO user_cart (cartproduct_id, cart_userid, product_quantity) VALUES (?, ?, ?)");
        $insertQuery->bind_param('iii', $productID, $userID, $quantity);

        if ($insertQuery->execute()) {
            echo "
                <script>
                    alert('Item added to cart');                    
                </script>";
        } else {
            //echo "Error executing query.";
        }
    }
    
} else {
    echo "
        <script>
            alert('Item is already in cart');            
        </script>";
}

echo "<script>window.location.href='/'</script>";
