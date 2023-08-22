<?php
session_start();


$con = mysqli_connect("database","Lottie", "Ad0r@ble", "websafe");

if (!$con) {
    die("Failed to connect " . mysqli_connect_errno());
}

if (isset($_POST["form_submit"])) {
    // Page is loaded because of a form
    $comment = $_POST["comment_content"];
    $uid = $_SESSION["user_id"];

    $query = $con->prepare('INSERT INTO `comments` (`user_id`,`comment`) VALUES (?,?)');
    $query->bind_param('is', $uid, $comment);

    if ($query->execute()) {
        // echo "Query executed.";
        header("Location: /comment");
    } else {
        echo "Error executing query.";
    }
    
    $con->close();
    
} else {
    // Page is manually loaded without a form (unauthorized access)
    $con->close();
    header("Location: /comment");
}

?>