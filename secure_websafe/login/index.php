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

//CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

$con = mysqli_connect("database", "Lottie", "Ad0r@ble", "websafe");

if (!$con) {
    die("Failed to connect: " . mysqli_connect_errno());
}

$error = ""; // Variable to store the error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate reCAPTCHA response
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
        // Error occurred while making the reCAPTCHA verification request
        echo "reCAPTCHA verification failed. Please try again later.";
        exit();
    } else {
        $response = json_decode($result);

        if (!$response->success) {
            // reCAPTCHA verification failed
            $error = "reCAPTCHA verification failed. Please make sure you are not a robot.";
        }
    }

    if (empty($error)) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $query = $con->prepare("SELECT * FROM `users` WHERE `username` = ?");
        $query->bind_param('s', $username);
        
        if ($query->execute()) {
            $result = $query->get_result();
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {

                //CWE-312 secure
                $ciphering = "AES-256-GCM";
                $iv_length = openssl_cipher_iv_length($ciphering);
                $encryption_key = random_bytes($iv_length); // Use random_bytes for a secure key
                $encryption_iv = openssl_random_pseudo_bytes($iv_length); // Use openssl_random_pseudo_bytes for a secure IV
                $options = OPENSSL_RAW_DATA;
                $encryption = openssl_encrypt($user["user_id"], $ciphering, $encryption_key, $options, $encryption_iv, $tag); // Include the $tag variable to store the authentication tag
                $encryptedID = base64_encode($encryption);
                $_SESSION['encryptionKey'] = $encryption_key;
                $_SESSION['encryptionIv'] = $encryption_iv;
                $_SESSION['authenticationTag'] = $tag;
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["privilege"] = $user["privilege"];
                echo '<script>sessionStorage.setItem("UID", "' . $encryptedID . '");</script>'; //stores encrypted UID of the user in the sessionstorage
                date_default_timezone_set('Singapore');
                $date = date('y-m-d h:i:s');
                $logName = $_SESSION['username'];
                $auditRole = $_SESSION['privilege'];
                $auditActivity = 'Account logged in';
                $auditquery = $con->prepare("INSERT INTO audit_trail (audit_username, audit_role, audit_datetime, audit_activity) VALUES (?,?,?,?)"); //audit logs of users logging in
                $auditquery->bind_param('ssss', $logName, $auditRole, $date, $auditActivity);
                if ($auditquery->execute()) {
                    $con->close();
                    // echo "Audit log has been captured";
                    // Redirect to the homepage or any other authenticated page
                    echo "<script>window.location.href='/'</script>";
                }
            } else {
                $error = "Invalid username or password.";
                date_default_timezone_set('Singapore'); //set date and time of log
                $date = date('d-m-y h:i:s');
                $logName = "NIL";
                $auditRole = "NIL";
                $auditActivity = "Failed to Login with username: $username";

                $auditquery = $con->prepare("INSERT INTO audit_trail (audit_username, audit_role, audit_datetime, audit_activity) VALUES (?,?,?,?)"); //audit logs of users logging in
                $auditquery->bind_param('ssss', $logName, $auditRole, $date, $auditActivity);
                $auditquery->execute();
            }
        } else {
            $error = "Error executing query.";
            $con->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/sex.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <?php include "../navbar.php"; ?>

    <div class="container">

        <div class="login_card">
            <h1>LOGIN</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!-- <form method="POST" action="test.php"> -->
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <div align="center" class="g-recaptcha" data-sitekey="6LdgKDAnAAAAADK49r3eybJWd9kyoE32PIO-ixkt"></div>
                <input type="submit" value="Login" class="button" name="form_submit">
            </form>

            <?php
            if (!empty($error)) {
                echo '<p class="error">' . $error . '</p>';
            }
            ?>
        </div>

        <!-- THIS LEADS TO THE INSECURE VERSION OF BOTH -->
        <div class="forgor">
            <span>Not Registered? <a href="/register">Register Here</a></span>
            <span><a href="/login/reset">Forgot Password?</a></span>
        </div>


    </div>
</body>

</html>