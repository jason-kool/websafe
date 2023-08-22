<?php

if (isset($_GET["com_id"])) {
    $comment_id = $_GET["com_id"];
   
    include "../sql_con.php";

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