<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/design.css">
    <title>Admin</title>
</head>
<body>
    
    <?php
    include "../../navbar.php";
    include "../adminbar.php";
    ?>

    <div class="adminmain">
        <form action="" method="get">
            <select name="vuln" id="">
                <option value="http://192.168.20.22/welcome.html">Internal Server Status</option>
            </select>
            <button type="submit">Submit</button>
        </form>
    </div>


    <div class="codeblock">

        <?php      
            if (isset($_GET["vuln"])) {

                echo "<code><span>";
            
                $request = file_get_contents($_GET["vuln"]);
                echo $request;
                
                echo "</span></code>";
            }
        ?>
    </div>

</body>
</html>