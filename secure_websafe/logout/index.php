<?php
session_start(); // Start the session
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
}

$con = mysqli_connect("database", "Lottie", "Ad0r@ble", "websafe");

// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
if (!$con) {
    die("Failed to connect: " . mysqli_connect_errno());
}

// Check if the user is logged in
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    $date = date('d-m-y h:i:s');
    $logName = $_SESSION["username"];
    $logRole = $_SESSION["privilege"];
    $logActivity = "Account logged out";

    $logquery = $con->prepare("INSERT INTO audit_trail (audit_username, audit_role, audit_datetime, audit_activity) VALUES (?,?,?,?)"); //audit logs of users logging in
    $logquery->bind_param('ssss', $logName, $logRole, $date, $logActivity);
    if ($logquery->execute()) {
        $con->close();
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
    }
    echo "<script>
    sessionStorage.clear();
    window.location.href='/';
    </script>";
}
// header("Location: /");
exit();
?>
