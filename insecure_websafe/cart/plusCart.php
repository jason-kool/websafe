<?php
session_start();
if (!isset($_SESSION["user_id"])){
    header("Location: /");
}
$con = mysqli_connect("insecure_database", "Lottie", "Ad0r@ble", "websafe");

if (!$con) {
    die("Failed to connect " . mysqli_connect_errno());
}

// increase product quantity from cart
$productID = $_GET['product_id'];
$UID = $_GET['UID'];
$query = $con->prepare("UPDATE user_cart SET product_quantity = product_quantity + 1 WHERE cartproduct_id=? and cart_userid=?");
$query->bind_param('ii', $productID, $UID);

if ($query->execute()) {
    $con->close();
    // echo "query executed.";
    echo "
    <script>
    window.onload = history.back();
    </script>";
} else {
    echo "Error executing query.";

}
?>