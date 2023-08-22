<?php
if (!isset($_SESSION["user_id"])){
    header("Location: ../");
}
include "../init-error.php";
include "../sql_con.php";

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