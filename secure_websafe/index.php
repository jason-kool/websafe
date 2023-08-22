<?php
include "./init-timeout.php";
include "./init-error.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Websafe shop</title>
    <link rel="stylesheet" type="text/css" href="./design.css">
</head>

<body>

    <?php include "./navbar.php"; ?>

    <?php

    include "./sql_con.php";

    function getAllProducts()
    {
        global $con;
        $query = $con->prepare("SELECT * FROM `products`");
        // SELECT everything FROM a table called `products`

        if ($query->execute()) {
            $query->bind_result($product_id, $name, $price, $picture, $description);
            $query->store_result();

            while ($query->fetch()) {
                echo '<div class="cell" data-product-id="' . $product_id . '"  data-description="' . $description . '"><div class="product"><img src="./productimages/' . $picture . '" alt="Image of ' . $name . '"><div class="product-details"><h2>' . $name . '</h2><p>$' . $price . '</p><br>';

                if (isset($_SESSION["user_id"])) {
                    echo '<a href="/cart/addtoCart.php?product_id=' . $product_id . '" class="cell-btn">Add to cart</a>';
                }

                echo '</div></div></div></div>';
            }
        } else {
            echo "Error executing query.";
        }
    }


    try {
        if (isset($_POST["search_query"])) {
            if ($_POST["search_query"] == null) {
                getAllProducts();
            } else {

                $query = $con->prepare("SELECT * FROM `products` WHERE `name` LIKE CONCAT ('%', ?, '%')");
                $query->bind_param("s", $_POST["search_query"]);
                if ($query->execute()) {
                    $query->bind_result($product_id, $name, $price, $picture, $description);
                    $query->store_result();

                    while ($query->fetch()) {
                        echo '<div class="cell" data-product-id="' . $product_id . '"  data-description="' . $description . '"><div class="product"><img src="./productimages/' . $picture . '" alt="Image of ' . $name . '"><div class="product-details"><h2>' . $name . '</h2><p>$' . $price . '</p><br>';

                        if (isset($_SESSION["user_id"])) {
                            echo '<a href="/cart/addtoCart.php?product_id=' . $product_id . '" class="cell-btn">Add to cart</a>';
                        }

                        echo '</div></div></div></div>';
                    }
                } else {
                    echo "Error executing query.";
                }
            }
        } else {
            throw new Exception("No searches made");
        }
    } catch (\Throwable $th) {
        //throw $th;
        getAllProducts();
    }


    ?>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="product-details">
                <h2 id="product-name"></h2>
                <div class="product-image">
                    <img id="product-image" alt="Product Image">
                </div>
                <p id="product-description"></p>
                <p id="product-price"></p>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
        </div>
    </div>

    <script>
        // Get the modal element
        var modal = document.getElementById("myModal");

        // Get the <span> element that closes the modal
        var closeBtn = document.getElementsByClassName("close")[0];

        // Get the placeholder elements inside the modal
        var productNamePlaceholder = document.getElementById("product-name");
        var productImagePlaceholder = document.getElementById("product-image");
        var productDescriptionPlaceholder = document.getElementById("product-description");
        var productPricePlaceholder = document.getElementById("product-price");
        var addToCartButton = document.querySelector(".add-to-cart-btn");



        // Add click event listeners to each cell
        var cells = document.getElementsByClassName("cell");
        for (var i = 0; i < cells.length; i++) {
            cells[i].addEventListener("click", function() {
                // Get the product details from the clicked cell
                var productName = this.querySelector("h2").textContent;
                var productImage = this.querySelector("img").src;
                var productDescription = this.querySelector(".product-details p").textContent;
                var productPrice = this.querySelector("p").textContent;
                var productId = this.getAttribute("data-product-id");

                // Set the modal content with the product details
                productNamePlaceholder.textContent = productName;
                productImagePlaceholder.src = productImage;
                productDescriptionPlaceholder.textContent = this.getAttribute("data-description");
                productPricePlaceholder.textContent = productPrice;

                // Add click event listener to the "Add to Cart" button
                addToCartButton.addEventListener("click", function() {
                    <?php if (isset($_SESSION["user_id"])) { ?>
                        location.href = "/cart/addtoCart.php?product_id=" + productId;
                    <?php } else { ?>
                        alert("Please log in before adding to cart.");
                        location.href = "/login/";
                    <?php } ?>
                });

                // Display the modal
                modal.style.display = "block";
            });
        }

        // Close the modal when the user clicks on the close button
        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        // Close the modal when the user clicks outside of it
        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    </script>

</body>

</html>