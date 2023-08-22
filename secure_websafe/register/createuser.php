<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /");
}
$con = mysqli_connect("database","Lottie", "Ad0r@ble", "websafe");

// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
if (!$con) {
    die("Failed to connect: " . mysqli_connect_errno());
}

$query = $con->prepare("INSERT INTO `users` (`username`, `password`, `email`, `privilege`) VALUES (?,?,?, 'user')");

$query->bind_param('ssss', $username, $password, $email, $privilege); 

if ($query->execute()) {
    echo "Query executed.";
} else {
    echo "Error executing query.";
}

$con->close(); 

?>