<?php
session_start();
include "../init-timeout.php";
include "../init-error.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" type="text/css" href="/design.css">
</head>

<body>
    <?php include "../navbar.php"; ?>
    <br>
    <div class="cart_contents">

        <script>
            var n = sessionStorage.getItem('UID');
            
            // Send the value of n to the server-side PHP script
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cart.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Update the table with the response from the server
                    document.getElementsByClassName('cart_contents')[0].innerHTML += xhr.responseText;
                }
            };
            // xhr.send('cartID=' + n);
            xhr.send('UID='+n);
        </script>

        <script>
            window.addEventListener('pageshow', function (event) {
                // Reload the page only if it's not from the cache
                if (event.persisted) {
                    window.location.reload();
                }
            });
            history.pushState(null, null, location.href)
        </script>

    </div>
    


</body>