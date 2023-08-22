<?php
$timeout = 300;
ini_set("session.gc_maxlifetime", $timeout);
ini_set("session.cookie_lifetime", $timeout);
session_start();
$s_name = session_name();
    if (isset($_COOKIE[$s_name])) {
        setcookie($s_name, $_COOKIE[$s_name], time() + $timeout, '/');
    } else {
        if (session_destroy()) {
            echo "
            <script>
                alert('Sorry, you have been inactive for too long. Please log in again.');
                window.location.href='login.php';
            </script>";
        }
    }
if (!isset($_SESSION["user_id"])) {
    header("Location: /");
}

$con = mysqli_connect("database","Lottie", "Ad0r@ble", "websafe");

// CWE-209: Generation of Error Message Containing Sensitive Information
// error_reporting(E_ERROR | E_PARSE);
// ini_set('display_errors', 0);
if (!$con) {
    die("Failed to connect " . mysqli_connect_errno());
}
?>

<?php
//CWE-284
if (isset($_POST['UID'])) {
    // CWE-312 secure
    $user_id = $_POST["UID"];
    $ciphering = "AES-256-GCM";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $encryption_key = $_SESSION['encryptionKey'];
    $options = OPENSSL_RAW_DATA;
    $encryption_iv = $_SESSION['encryptionIv'];
    $tag = $_SESSION['authenticationTag'];
    $decodedID = base64_decode($user_id);
    $decryptedID = openssl_decrypt($decodedID, $ciphering, $encryption_key, $options, $encryption_iv, $tag);
    if ($decryptedID == $_SESSION["user_id"]) { //CWE-285 makes sure that the user cannot access carts of other users
            $query = "SELECT product_quantity, cartproduct_id, name, price, picture FROM websafe.user_cart
            INNER JOIN websafe.products ON cartproduct_id = product_id
            INNER JOIN websafe.users ON cart_userid = user_id WHERE user_id = ?";          

            $pQuery = $con->prepare($query);
            $pQuery->bind_param("i", $decryptedID);
            $result = $pQuery->execute();
            $result = $pQuery->get_result();

            $nrows = $result->num_rows;

            if ($nrows > 0) { //If there are outputs

                $productArray = [];

                while ($row = $result->fetch_assoc()) {
                    $productArray[] = $row;
                }

                echo '<div id="product_container">';

                // for every row fetched in the table
                foreach ($productArray as $product_row) {
                    $itemPrice = $product_row['price']; // price of each item
                    $quantity = $product_row['product_quantity']; // quantity of each product
                    $picture = $product_row['picture'];
                    $product_name = $product_row['name'];
                    $product_id = $product_row['cartproduct_id'];

                    echo '<div class="cart_cell"><img src="/productimages/'.$picture.'" class="cart_cell_img"><div class="cart_cell_item-left"><h1>'.$product_name.'</h1><h2>$'.$itemPrice.'</h2></div><div class="cart_cell_item-right"><div id="ccir_row"><a href="plusCart.php?product_id='.$product_id.'&UID='.$decryptedID.'"><button>+</button></a><span>'.$quantity.'</span><a href="minusCart.php?product_id='.$product_id.'&UID='.$decryptedID.'"><button>&minus;</button></a></div><br><a href="deleteCart.php?product_id='.$product_id.'&UID='.$decryptedID.'"><button>Delete</button></a></div></div>';
                }
                    

                echo '</div>';
                echo '<div class="checkout"><h1>Order Summary</h1><table border="0" class="lmao"><thead><tr><th><b></b></th><th class="lmao"><b></b></th><th><b></b></th></tr></thead><tbody>';

                $totalQuantity = null;
                $totalPrice = null;
                foreach ($productArray as $product_row) {
                    $totalQuantity += $product_row['product_quantity']; // sum of number of all products in cart
                    $itemPrice = $product_row['price']; // price of each item
                    $quantity = $product_row['product_quantity']; // quantity of each product
                    $itemsPrice = $itemPrice * $quantity; // total price of each product
                    $price = number_format((float) $itemsPrice, 2, '.', ''); // price of unique product
                    $totalPrice += $price; 
                    $cumulative = number_format((float) $totalPrice, 2, '.', ''); // grand total of all products
                    $picture = $product_row['picture'];
                    $product_name = $product_row['name'];
                    $product_id = $product_row['cartproduct_id'];


                    echo '<tr><td><span>'.$quantity.'&times;</span></td><td>'.$product_name.'</td><td>$'.$price.'</td></tr>';

                }
                echo '</tbody></table><hr>';
                echo '<table><tbody class="lmao"><tr><td colspan="2" class="lmao">Grand total</td>';
                echo '<td>$'.$cumulative.'</td></tr></tbody></table>';
                echo '<br><button id="checkout_button">checkout</button>';
                echo '</div>';

            } else { // if the cart is empty
                echo '<div id="product_container"></div>';
                echo '<div class="checkout"><h1>Order Summary</h1><table border="0" class="lmao"><thead><tr><th><b>NO ITEMS IN CART</b></th></tr></thead></table></div>';
            }
        } else { // If the cartID does not match the user ID
            echo "<div class='no_cart'>";
            echo "THIS IS NOT YOUR CART" . "<br>" . "Unauthorized or improper use of this system may result in administrative disciplinary action, civil charges, and/or criminal penalties";
            echo "</div>"; 
        }
    }
?>