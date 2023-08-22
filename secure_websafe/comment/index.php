<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /");
}
// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="/sex.css">
</head>

<body>
    <?php include "../navbar.php"; ?>

    <div class="commentscontainer">
        <h1>Leave a message for us!</h1>
        <form action="post_comment.php" method="post" id="comment_form">
            <textarea name="comment_content" rows="15" placeholder="Write your comment here!" form="comment_form"></textarea>
            <input type="submit" value="Post comment" class="button" name="form_submit">
        </form>
    </div>

    <?php
    
    $con = mysqli_connect("secure_database", "Lottie", "Ad0r@ble", "websafe");

    if (!$con) {
        die("Failed to connect " . mysqli_connect_errno());
    }

    $query = $con->prepare('SELECT `c`.*, `u`.`username`, `u`.`profilepicture` FROM `users` u INNER JOIN `comments` c ON `c`.`user_id` = `u`.`user_id` ORDER BY c.comment_id DESC;');

    if ($query->execute()) {

        $query->bind_result($comment_id, $user_id, $comment, $post_date, $username, $profilepic);
        $query->store_result();

        echo '<div class="commentscontainer">';

        while ($query->fetch()) {
            echo '<div class="comment_cell"><div class="comment_cell_left">';
            
            if (empty($profilepic)) {
                echo '<img src="/iamges/user_profiles/profile.jpg" alt="">';
            } else {
                echo '<img src="/iamges/user_profiles/'.$profilepic.'" alt="">';
            }
            
            
            echo '<h2>'.$username.'</h2></div><div class="comment_cell_right"><p>'.$comment.'</p><div class="date_posted"><span>';

            if (isset($_SESSION["user_id"]) && $user_id == $_SESSION["user_id"]) {
                echo '<a href="delete_comment.php?com_id='.$comment_id.'">Delete this comment</a>';
            }


            echo 'Posted on '.$post_date.'</span></div></div></div>';
            
        }

        echo '</div>';


    } else {
        echo "Error executing query.";
    }

    $con->close();
    

    ?>

</body>

</html>