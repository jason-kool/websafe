<?php
include "../../init-timeout.php";
include "../../init-error.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}


$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["password"]) && isset($_POST["confirm_password"])) {
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm_password"];
        if ($password === $confirmPassword) {
            $email = $_SESSION["update_email"];
            include "../../sql_con.php";
            // CWE-20 Improper input validation
            $passwordCheck = preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password);
            // CWE-261: Weak Encoding for Password (Secure Version)
            $timeTarget = 0.05;
            $cost = 8; // minimum number of operations necessary to compute the password hash
            do {
                $cost++;
                $start = microtime(true);
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]); // salted hash function (salt is randomly generated)
                $end = microtime(true);
            } while (($end - $start) < $timeTarget); // as long as the code has been running for less than 50 milliseconds, the cost increases by one
            if ($passwordCheck) {
                $updateQuery = $con->prepare("UPDATE `users` SET `password` = ? WHERE `email` = ?");
                $updateQuery->bind_param('ss', $hashedPassword, $email);

                if ($updateQuery->execute()) {
                    unset($_SESSION["update_email"]);
                    header("Location: /login");
                    exit();
                } else {
                    $error = "Failed to update the password. Please try again later.";
                }
                mysqli_close($con);
            } else {
                $error = "Ensure that your password contains at least 8 characters, a capital letter, and a small letter";
            }
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "Invalid form data. Please make sure all fields are filled.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link rel="stylesheet" type="text/css" href="/design.css">
</head>

<body>
    <?php include "../../navbar.php"; ?>

    <div class="update_cell">
        <h2 style="color:white" align="center">Update Password</h2>
        <?php
        if (!empty($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="update_form">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="update_form">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="update_form">
                <input type="submit" value="Update Password" class="btn-login">
            </div>
        </form>
    </div>
</body>

</html>