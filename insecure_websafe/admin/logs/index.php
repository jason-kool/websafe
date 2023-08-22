<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: /");
}

if ($_SESSION["privilege"] != "admin"){
  header("Location: /");
}

$con = mysqli_connect("insecure_database", "Lottie", "Ad0r@ble", "websafe");
if (!$con) {
  die("Failed to connect: " . mysqli_connect_errno());
}

function getAllLogs()
{
  global $con;
  $query = "SELECT * FROM `audit_trail`";
  $pQuery = $con->prepare($query);
  $result = $pQuery->execute();
  $result = $pQuery->get_result();
  return $result;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Helpme</title>
  <link rel="stylesheet" type="text/css" href="/sex.css">
</head>

<body>
  <?php 
  include "../../navbar.php";
  include "../adminbar.php";
  ?>

  <div>
    <table>
      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input id="logsearchbar" type="text" id="navbar_search" name="search_query" placeholder="Log search"></input>
        <button class="logsearchbutton" type="submit">Search</button>
      </form>
    </table>
  </div>

  <?php
  error_reporting(E_ERROR | E_PARSE);

  if ($_SESSION["privilege"] == "admin") {
    try {
      if (isset($_POST["search_query"])) {
        if ($_POST["search_query"] == null) {
          $result = getAllLogs();
        } else {
          $search_query = "%" . $_POST["search_query"] . "%";
          $query = $con->prepare("SELECT * FROM `audit_trail` WHERE `audit_username` LIKE ? OR `audit_role` LIKE ? OR `audit_activity` LIKE ?");
          $query->bind_param("sss", $search_query, $search_query, $search_query);
          $query->execute();
          $result = $query->get_result();
        }
      } else {
        throw new Exception("No searches made");
      }
    } catch (\Throwable $th) {
      // Proper error handling should be implemented here.
      $result = getAllLogs();
    }

    $nrows = $result->num_rows;

    if ($nrows > 0) {
      echo "<br>";
      echo "<div class='logtable'>";
      echo "<table border=1 align=center style='width:50vw;color:white'>";
      echo "<tr>";
      echo "<th style='position:sticky;top:0;background:black;'>ID</th>";
      echo "<th style='position:sticky;top:0;background:black;'>Username</th>";
      echo "<th style='position:sticky;top:0;background:black;'>Role</th>";
      echo "<th style='position:sticky;top:0;background:black;'>Date & Time</th>";
      echo "<th style='position:sticky;top:0;background:black;'>Activity</th>";
      echo "</tr>";

      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>";
        echo $row['id'];
        echo "</td>";
        echo "<td>";
        echo $row['audit_username'];
        echo "</td>";
        echo "<td>";
        echo $row['audit_role'];
        echo "</td>";
        echo "<td>";
        echo $row['audit_datetime'];
        echo "</td>";
        echo "<td>";
        echo $row['audit_activity'];
        echo "</td>";
        echo "</tr>";
      }
      echo "</div>";
      echo "</table>";
    } else {
      echo "0 records<br>";
    }
  } else {
    echo "<div class='no_cart'>";
    echo "THIS IS FOR ADMINISTRATORS ONLY" . "<br>" . "Unauthorized or improper use of this system may result in administrative disciplinary action, civil charges/criminal penalties";
    echo "</div>";
  }
  ?>

</body>

</html>