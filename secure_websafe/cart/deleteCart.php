<?php
session_start();
if (!isset($_SESSION["user_id"])){
    header("Location: ../");
}
$con = mysqli_connect("database","Lottie", "Ad0r@ble", "websafe");

// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
if (!$con) {
    die("Failed to connect " . mysqli_connect_errno());
}

// increase product quantity from cart
$productID = $_GET['product_id'];
$UID = $_GET['UID'];
$query = $con->prepare("DELETE FROM user_cart WHERE cartproduct_id=? and cart_userid=?");
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