<?php

if (isset($_GET["com_id"])) {
    $comment_id = $_GET["com_id"];
   
    $con = mysqli_connect("insecure_database", "Lottie", "Ad0r@ble", "websafe");
    if (!$con) {
        die("Failed to connect " .  mysqli_connect_errno());
    }

    $query = $con->prepare('DELETE FROM `comments` WHERE `comment_id` = ?');
    $query->bind_param("i", $comment_id);

    if ($query->execute()) {
        header("Location: /comment");
    } else {
        echo "Error executing query.";
    }

    $con->close();

} else {
    header("Location: /");
}

?>