<?php
    $userToDelete = $_GET['user_id'];
    // echo $userToDelete;

    // $con = mysqli_connect("localhost","root", "", "websafe");
    $con = mysqli_connect("database","Lottie", "Ad0r@ble", "websafe");

    if (!$con) {
        die("Failed to connect " . mysqli_connect_errno());
    }
    
    $query = $con->prepare("DELETE FROM `users` WHERE `user_id` = ?");

    $query->bind_param("i",$userToDelete);

    if ($query->execute()) {
        $con->close();
        header("location: ./index.php");
        header("location: ./");
    } else {
        echo "Error executing query";
    }
?>