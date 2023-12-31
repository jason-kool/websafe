<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: /");
}

// CWE-862: Missing Authorization
// {
//     ?????????????
// }

function create_product() {
    // moving POST arguments into variables
    $productName = $_POST["name"];
    $productPrice = $_POST["price"];
    $productDesc = $_POST["description"];
    $productQuantity = $_POST["quantity"];

    // redirect if image is not included in product creation
    $productImage = $_FILES["product_image"];
    if (empty($productImage["size"])) {
        $GLOBALS['$error'] = "No image included";
    }

    // storing POST image as file in local filesystem
    // CWE-434: Unrestricted Upload of File with Dangerous Type
    $fileName = $productImage["name"];
    $fileTmpName = $productImage["tmp_name"];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = "product_" . preg_replace('/\s+/', '_', $productName) . "." . $fileExtension;
    $fileDestination = "../../productimages/" . $newFileName; // path to directory relative from current position
    
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        include "../../sql_con.php";


        // if (isset($_POST["create_product"])) {
        if (isset($_POST["form_action"]) && $_POST["form_action"] == "create") {
            $query = $con->prepare("INSERT INTO `products` (`name`,`price`,`picture`,`description`) VALUES (?,?,?,?)");
            $query->bind_param('sdss', $productName, $productPrice, $newFileName, $productDesc);
            if ($query->execute()) {
                $con->close();
            } else {
                $con->close();
                $GLOBALS['$error'] = "Error executing query.";
                
            }
        }
    } else {
        $GLOBALS['$error'] = "Error uploading image.";
    }
}

function update_product() {
    // moving POST arguments into variables
    $idToUpdate = $_POST["id"];
    $updatedName = $_POST["name"];
    $updatedPrice = $_POST["price"];
    $updatedDesc = $_POST["description"];

    // checks if ID is not specified
    if (empty($idToUpdate)) {
        $GLOBALS['$error'] = "ID must be specified";
        
    }

    include "../../sql_con.php";


    // check if the product with the specified ID exists
    $checkQuery = $con->prepare("SELECT * FROM `products` WHERE `product_id` = ?");
    $checkQuery->bind_param("i",$idToUpdate);

    if ($checkQuery->execute()) {
        $checkResult = $checkQuery->get_result();
        
        if ($checkResult->num_rows == 1) { // check that only one product match this row
            // bind current details of product into variables
            $product = $checkResult->fetch_assoc();
            $product_id = $product["product_id"];
            $currentName = $product["name"];
            $currentPrice = $product["price"];
            $currentPicture = $product["picture"];
            $currentDesc = $product["description"];

        } else {
            $GLOBALS['$error'] = "This product does not exist";
            
        }


        $newProductImage = $_FILES["product_image"];
        if (!empty($newProductImage["size"])) { // if there is an image uploaded 

            if (empty($updatedName)) {
                $productName = $currentName;
            } else {
                $productName = $updatedName;
            }

            $fileName = $newProductImage["name"];
            $fileTmpName = $newProductImage["tmp_name"];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = "product_" . preg_replace('/\s+/', '_', $productName) . "." . $fileExtension;
            $fileDestination = "../../../productimages/" . $newFileName; // path to directory relative from current position
            
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $updateNameQuery = $con->prepare("UPDATE `products` SET `picture` = ? WHERE `product_id` = ?");
                $updateNameQuery->bind_param('si', $newFileName, $idToUpdate);
                $updateNameQuery->execute();
                $updateNameQuery->close();

                $productUpdated = true;
            }
        }

        if (!empty($updatedName)) {
            $updateNameQuery = $con->prepare("UPDATE `products` SET `name` = ? WHERE `product_id` = ?");
            $updateNameQuery->bind_param('si', $updatedName, $idToUpdate);
            $updateNameQuery->execute();
            $updateNameQuery->close();
    
            $productUpdated = true;
        }
    
        if (!empty($updatedPrice)) {
            $updatePriceQuery = $con->prepare("UPDATE `products` SET `price` = ? WHERE `product_id` = ?");
            $updatePriceQuery->bind_param('si', $updatedPrice, $idToUpdate);
            $updatePriceQuery->execute();
            $updatePriceQuery->close();
    
            $productUpdated = true;
        }
    
        if (!empty($updatedDesc)) {
            $updateDescQuery = $con->prepare("UPDATE `products` SET `description` = ? WHERE `product_id` = ?");
            $updateDescQuery->bind_param('si', $updatedDesc, $idToUpdate);
            $updateDescQuery->execute();
            $updateDescQuery->close();
    
            $productUpdated = true;
        }
        

    } else {
        $GLOBALS['$error'] = "Error executing query.";
    }
}

function delete_product() {

    // checks if an ID is mentioned or not
    if (!isset($_GET["id"])) {
        $GLOBALS['$error'] = "ID must be specified";
    } else {
        $idToDelete = $_GET["id"];
    
    
        include "../sql_con.php";
    
        $checkQuery = $con->prepare("SELECT * FROM `products` WHERE `product_id` = ?");
        $checkQuery->bind_param("i",$idToDelete);
    
        if ($checkQuery->execute()) {
            $checkResult = $checkQuery->get_result();
            if ($checkResult->num_rows == 1) { // check that only one product match this row
                $query = $con->prepare("DELETE FROM `products` WHERE `products`.`product_id` = ?");
                $query->bind_param("i", $idToDelete);
                if ($query->execute()) {
                    $con->close();
                    
                } else {
                    $con->close();
                    $GLOBALS['$error'] = "Error executing query";
                }
            } else {
                $GLOBALS['$error'] = "This product does not exist";
            }
        }
        $con->close();
    }

}


if (isset($_POST["form_action"])) {
    if ($_POST["form_action"] == "create") {
        create_product();
    }
    
    if ($_POST["form_action"] == "update") {
        update_product();
    }
}

if (isset($_GET["action"]) && $_GET["action"] = "delete") {
    delete_product();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/design.css">
    <title>Manage Products</title>
</head>

<body>

    <?php
        include "../../navbar.php";
        include "../adminbar.php";

        // CWE-565: Reliance on Cookies without Validation and Integrity Checking​
        // checks for the privilege cookie given in the login page
        if ((!isset($_COOKIE["privilege"])) || ($_COOKIE["privilege"] != "admin")) {
            die("<div class='no_cart'>THIS IS FOR ADMINISTRATORS ONLY<br>Unauthorized or improper use of this system may result in administrative disciplinary action, civil charges/criminal penalties</div>");
        }
    ?>



    <div class="admincontainer">

        <?php
            include "../../sql_con.php";

            $query = $con->prepare("SELECT * FROM `products`");
            // SELECT everything FROM a table called `products`

            if ($query->execute()) {
                $query->bind_result($product_id,$name,$price,$picture,$description);
                $query->store_result();

                echo '<table border="1" align="center">';
                echo '<thead><tr><th colspan="6">Current list of products</th></tr><tr><th><b>Product ID</b></th><th><b>Product Name</b></th><th><b>Price</b></th><th><b>Picture</b></th><th><b>Description</b></th></tr></thead><tbody>';


                while ($query->fetch()) {
                    echo '<tr><td>'.$product_id.'</td><td>'.$name.'</td><td>'.$price.'</td><td>'.$picture.'</td><td>'.$description.'</td><td><a href="?action=delete&id='.$product_id.'">delete</td></tr>';
                }

                if (!empty($GLOBALS['$error'])) {
                    echo '<tr><th colspan="7">'.$GLOBALS['$error'].'</tr></th>';
                }

                echo '</tbody></table>';  
            } else {
                echo "Error executing query.";
            }
        ?>  
        <br>
        <br>

        <form action="./" method="post" enctype="multipart/form-data">
        <table border="1" align="center">
            <tr>
                <td>Product ID:</td>
                <td><input type="text" name="id"></td>
            </tr>
            <tr>
                <td>Product Name:</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td>Product Price:</td>
                <td><input type="text" name="price"></td>
            </tr>
            <tr>
                <td>Product Picture:</td>
                <td><input type="file" id="file" name="product_image" accept="image/jpg, image/jpeg, image/png"></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea name="description" rows="7" maxlength="400"></textarea></td>
            </tr>
            <tr>
                <th colspan="2"><input type="radio" name="form_action" id="create" value="create">
            <label for="create">Create product</label><input type="radio" name="form_action" id="update" value="update">
            <label for="">Update product</label></th>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="form_submit" value="Submit"></td>
            </tr>
            </table>
        </form>

    </div>

</body>

</html>