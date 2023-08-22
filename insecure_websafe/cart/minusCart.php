<?php
session_start();
if (!isset($_SESSION["user_id"])){
    header("Location: ../");
}

include "../sql_con.php";

// decrease product quantity from cart
$productID = $_GET['product_id'];
$UID = $_GET['UID'];

$query = $con->prepare("UPDATE user_cart SET product_quantity = product_quantity - 1 WHERE cartproduct_id=? AND cart_userid=?");
$query->bind_param('ii', $productID, $UID);

if ($query->execute()) {
    // Check if the quantity has become zero
    $query = $con->prepare("SELECT product_quantity FROM user_cart WHERE cartproduct_id=? AND cart_userid=?");
    $query->bind_param('ii', $productID, $UID);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $quantity = $row['product_quantity'];

    if ($quantity == 0) {
        // Delete the product from the cart
        $query = $con->prepare("DELETE FROM user_cart WHERE cartproduct_id=? AND cart_userid=?");
        $query->bind_param('ii', $productID, $UID);
        $query->execute();
    }

    $con->close();
    echo "
    <script>
    window.onload = history.back();
    </script>";
} else {
    echo "Error executing query.";
}
?>
