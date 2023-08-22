<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Wall</title>
    <link rel="stylesheet" type="text/css" href="/design.css">
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
    
    include "../sql_con.php";

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