<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /");
}
include "../init-error.php";

include "../sql_con.php";

if (isset($_POST["form_submit"])) {
    // CWE-79: Improper Neutralization of Input During Web Page Generation
    $comment = $_POST["comment_content"];
    $uid = $_SESSION["user_id"];
    $specialCharComment = htmlspecialchars($comment);

    $query = $con->prepare('INSERT INTO `comments` (`user_id`,`comment`) VALUES (?,?)');
    $query->bind_param('is', $uid, $specialCharComment);

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
