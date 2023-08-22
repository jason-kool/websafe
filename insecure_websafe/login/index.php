<?php
session_start();


include "../sql_con.php";

$error = ""; // Variable to store the error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    // CWE-261: Weak Encoding for Password
    $encoded_pass = base64_encode($password);
    // CWE-89: Improper Neutralization of Special Elements used in an SQL Command
    $query = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$encoded_pass'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $user = $result->fetch_assoc();
            if ($user) {
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["privilege"] = $user["privilege"];

                // THIS COOKIE IS USED ONLY FOR /admin/manage AND NOWHERE ELSE
                setcookie("privilege", $user["privilege"], 0, "/");
                // THIS COOKIE IS USED ONLY FOR /admin/manage AND NOWHERE ELSE

                echo '<script>sessionStorage.setItem("UID", "' . $user["user_id"] . '");</script>'; //stores encrypted UID of the user in the sessionstorage
                date_default_timezone_set('Singapore');
                $date = date('y-m-d h:i:s');
                $logName = $_SESSION['username'];
                $auditRole = $_SESSION['privilege'];
                $auditActivity = "Account $logName logged in with password $password";
                $auditquery = $con->prepare("INSERT INTO audit_trail (audit_username, audit_role, audit_datetime, audit_activity) VALUES (?,?,?,?)"); //audit logs of users logging in
                $auditquery->bind_param('ssss', $logName, $auditRole, $date, $auditActivity);
                if ($auditquery->execute()) {
                    $con->close();
                    // Redirect to the homepage or any other authenticated page
                    echo "<script>window.location.href='/'</script>";
                }
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Error executing query.";
        $con->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/design.css">
</head>

<body>
    <?php include "../navbar.php"; ?>

    <div class="container">

        <div class="login_card">
            <h1>LOGIN</h1>
            <form method="POST" action=".">
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <input type="submit" value="Login" class="button" name="form_submit">
            </form>

            <?php
            if (!empty($error)) {
                echo '<p class="error">' . $error . '</p>';
            }
            ?>
        </div>

        <div class="forgor">
            <span>Not Registered? <a href="/register">Register Here</a></span>
            <span><a href="/login/reset">Forgot Password?</a></span>
        </div>


    </div>
</body>

</html>