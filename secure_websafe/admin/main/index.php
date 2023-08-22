<?php
include "../../init-timeout.php";
include "../../init-error.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /");
  }

if ($_SESSION["privilege"] != "admin"){
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
                <option value="http://192.168.42.22/welcome.html">Internal Server Status</option>
            </select>
            <button type="submit">Submit</button>
        </form>
    </div>


    <div class="codeblock">

        <?php      
            if (isset($_GET["vuln"])) {

                echo "<code><span>";

                // $handle = fopen("../../../../../../../../etc/passwd","r","true");
                // $handle = fopen("http://192.168.42.22/welcome.html","r","true");
                // $handle = fopen($_GET["vuln"],"r","true");

                // echo fread($handle, filesize($_GET["vuln"]));
                // echo fread($handle, 2048);
                // fclose($handle);
            
                $request = file_get_contents($_GET["vuln"]);
                echo $request;
                
                echo "</span></code>";
            }
        ?>
    </div>

</body>
</html>