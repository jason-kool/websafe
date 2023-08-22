<?php
session_start();
//non secure update password
$con = mysqli_connect("insecure_database", "Lottie", "Ad0r@ble", "websafe");;

if (!$con) {
    die("Failed to connect: " . mysqli_connect_errno());
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = $con->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $query->bind_param('s', $email);

    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows == 1) {
            $updateQuery = $con->prepare("UPDATE `users` SET `password` = ? WHERE `email` = ?");
            $updateQuery->bind_param('ss', $password, $email);

            if ($updateQuery->execute()) {
                // Password updated successfully
                header("Location: /login");
                exit();
            } else {
                $error = "Failed to update password. Please try again later.";
            }
        } else {
            $error = "Invalid email. Please enter a valid email address.";
        }
    } else {
        $error = "Error executing query.";
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="/sex.css">
</head>

<body>
    <?php include "../../navbar.php"; ?>



    <div class="container">
        <div class="login_card">
            <h1>RESET YOUR PASSWORD</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <input type="submit" value="Reset Password" class="button" name="form_submit">
            </form>

            <?php
                if (!empty($error)) {
                    echo '<p class="error">' . $error . '</p>';
                }
            ?>
        </div>

        <div class="forgor">
            <span>Remember your password? <a href="/login">Login Here</a></span>
        </div>
    </div>

</body>

</html>