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
                window.location.href='/login';
            </script>";
  }
}

if (!isset($_SESSION["user_id"])) {
    header("Location: /");
  }

if ($_SESSION["privilege"] != "admin"){
    header("Location: /");
}

// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/sex.css">
    <title>Document</title>
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