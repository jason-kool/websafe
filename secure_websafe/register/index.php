<?php
// CWE-613: Insufficient Session Expiration (Secure Version)
$timeout = 300;
ini_set("session.gc_maxlifetime", $timeout);
ini_set("session.cookie_lifetime", $timeout);
session_start();
$s_name = session_name();
if (isset($_COOKIE[$s_name])) {
    setcookie($s_name, $_COOKIE[$s_name], time() + $timeout, '/');
} else {
    if (session_destroy()) {
        echo "
            <script>
                alert('Sorry, you have been inactive for too long. Please log in again.');
                window.location.href='/';
            </script>";
    }
}

// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '6LdgKDAnAAAAAKnhhx3CegMqMibBliG6GHEWn4SM'; // Replace with your reCAPTCHA secret key

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === false) {
        $error = "reCAPTCHA verification failed. Please try again later.";
    } else {
        $response = json_decode($result);
        if ($response->success) {
            $con = mysqli_connect("database", "Lottie", "Ad0r@ble", "websafe");

            if (!$con) {
                die("Failed to connect: " . mysqli_connect_errno());
            }

            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $privilege = "user"; // Default privilege for a registered user

            // CWE-20 Improper input validation
            $usernameCheck = preg_match('/^[a-z0-9_]+$/i', $username);
            $passwordCheck = preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password);
            $emailCheck = preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $email);

            // CWE-261: Weak Encoding for Password (Secure Version)
            $timeTarget = 0.05;
            $cost = 8; // minimum number of operations necessary to compute the password hash
            do {
                $cost++;
                $start = microtime(true);
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]); // salted hash function (salt is randomly generated)
                $end = microtime(true);
            } while (($end - $start) < $timeTarget); // as long as the code has been running for less than 50 milliseconds, the cost increases by one
            if ($usernameCheck) {
                if ($passwordCheck) {
                    if ($emailCheck) {

                        // Check if username or email already exists in the database
                        $checkUsernameQuery = $con->prepare("SELECT * FROM `users` WHERE `username` = ?");
                        $checkUsernameQuery->bind_param('s', $username);
                        $checkUsernameQuery->execute();
                        $usernameResult = $checkUsernameQuery->get_result();

                        $checkEmailQuery = $con->prepare("SELECT * FROM `users` WHERE `email` = ?");
                        $checkEmailQuery->bind_param('s', $email);
                        $checkEmailQuery->execute();
                        $emailResult = $checkEmailQuery->get_result();

                        if ($usernameResult->num_rows > 0) {
                            $error = "Username already exists.";
                        } elseif ($emailResult->num_rows > 0) {
                            $error .= "Email already exists.";
                        } else {
                            $query = $con->prepare("INSERT INTO `users` (`username`, `password`, `email`, `privilege`) VALUES (?, ?, ?, ?)");
                            $query->bind_param('ssss', $username, $hashedPassword, $email, $privilege);

                            if ($query->execute()) {
                                header("Location: /login");
                                exit();
                            } else {
                                $error = "Error registering user.";
                            }
                        }
                    } elseif (!empty($email)) {
                        $error .= "Ensure that your email is properly formatted";
                    }
                } elseif (!empty($password)) {
                    $error .= "Ensure that your password contains at least 8 characters, a capital letter, and a small letter";
                }
            } elseif (!empty($username)) {
                $error .= "Your username may only contain alphabets and underscores";
            }
            $con->close();
        } else {
            $error .= "reCAPTCHA verification failed. Please make sure you are not a robot.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="/sex.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <?php include "../navbar.php"; ?>

    <div class="container">

        <div class="login_card">
            <h1>REGISTER</h1>
            <form method="POST" action="./">
                <input type="email" placeholder="Email" name="email" required>
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <div align="center" class="g-recaptcha" data-sitekey="6LdgKDAnAAAAADK49r3eybJWd9kyoE32PIO-ixkt"></div>
                <input type="submit" value="Register" class="button" name="form_submit">
            </form>

            <?php
            if (!empty($error)) {
                echo '<p class="error">' . $error . '</p>';
            }
            ?>
        </div>

        <div class="forgor">
            <span>Have an account? <a href="/login">Login Here</a></span>
        </div>
    </div>


</body>

</html>