<?php

// $con = mysqli_connect("localhost","root", "", "websafe");
// $con = mysqli_connect("database","root", "w3bs@fe_ADmin", "websafe");
// $con = mysqli_connect("database","Lottie", "Ad0r@ble", "websafe");
$con = mysqli_connect("secure_database", "Lottie", "Ad0r@ble", "websafe");

if (!$con) {
die("Failed to connect " . mysqli_connect_errno());
}

?>