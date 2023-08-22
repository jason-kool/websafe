<?php
include "../../init-timeout.php";
include "../../init-error.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /");
  }

if ($_SESSION["privilege"] != "admin"){
    header("Location: /");
}

// CWE-918: Server-Side Request Forgery 
// Function to see if the URL is allowed or not
function outbound_allowed($url) {
    
    if (empty($url)) {
        return false;
    } else {
        $value = parse_url($url); // split the URL into sections of scheme/host/path
    }

    if (!empty($value["scheme"])) {
        $scheme = $value["scheme"];
    }
    
    if (!empty($value["host"])) {
        $host = $value["host"];
    }

    if (!empty($value["path"])) {
        $path = $value["path"];
    }

    if ($host != "192.168.40.22") {
        return false;
    }

    if ($path != "/welcome.html") {
        return false;
    }

    return true;
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
                <option value="http://192.168.40.22/welcome.html">Internal Server Status</option>
            </select>
            <button type="submit">Submit</button>
        </form>
    </div>


    <div class="codeblock">

        <?php      
            if (isset($_GET["vuln"])) {

                echo "<code><span>";

                if (outbound_allowed($_GET["vuln"])) {
                    $request = file_get_contents($_GET["vuln"]);
                    echo $request;
                } else {
                    echo "ERROR FETCHING PAGE";
                }
            
                
                echo "</span></code>";
            }
        ?>
    </div>

</body>
</html>