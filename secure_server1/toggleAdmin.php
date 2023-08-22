<?php
    $user = $_GET['user_id'];
    $priv = $_GET['priv'];

    switch ($priv) {
        case 'admin':
            $updatedPriv = "user";
            break;

        case 'user':
            $updatedPriv = "admin";
            break;
    }

    $con = mysqli_connect("secure_database","Lottie", "Ad0r@ble", "websafe");

    if (!$con) {
        die("Failed to connect " . mysqli_connect_errno());
    }
    
    $query = $con->prepare("UPDATE `users` SET `privilege` = ? WHERE `user_id` = ?");
    $query->bind_param("si",$updatedPriv,$user);


    if ($query->execute()) {
        $con->close();
        header("location: ./");
    } else {
        echo "Error executing query";
    }
?>