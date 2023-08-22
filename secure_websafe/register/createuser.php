<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /");
}

include "../init-error.php";

include "../sql_con.php";

$query = $con->prepare("INSERT INTO `users` (`username`, `password`, `email`, `privilege`) VALUES (?,?,?, 'user')");

$query->bind_param('ssss', $username, $password, $email, $privilege); 

if ($query->execute()) {
    echo "Query executed.";
} else {
    echo "Error executing query.";
}

$con->close(); 

?>