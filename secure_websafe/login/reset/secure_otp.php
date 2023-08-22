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
                window.location.href='login.php';
            </script>";
    }
}

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// CWE-209: Generation of Error Message Containing Sensitive Information
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST["otp"];
    if (isset($_SESSION["reset_email"]) && isset($_SESSION["reset_otp"])) {
        $email = $_SESSION["reset_email"];
        $storedOTP = $_SESSION["reset_otp"];
        if ($otp == $storedOTP && isOTPValid($email, $otp)) {
            unset($_SESSION["reset_email"]);
            unset($_SESSION["reset_otp"]);
            // Set the email and OTP in session variables for password update
            $_SESSION["update_email"] = $email;
            header("Location: secure_update_password.php");
            exit();
        } else {
            $error = "Invalid OTP.";
        }
    }
}

function isOTPValid($email, $otp)
{
    $con = mysqli_connect("database", "Lottie", "Ad0r@ble", "websafe");
    if (!$con) {
        die("Failed to connect: " . mysqli_connect_errno());
    }

    $query = $con->prepare("SELECT * FROM `otp_requests` WHERE `email` = ? AND `otp` = ? AND `created_at` >= NOW() - INTERVAL 10 MINUTE");
    $query->bind_param('ss', $email, $otp);
    $query->execute();
    $result = $query->get_result();
    mysqli_close($con);
    return $result->num_rows >= 1;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" type="text/css" href="/sex.css">
</head>

<body>
    <?php include "../../navbar.php"; ?>
    <h2 style="color:white" align="center">OTP Verification</h2>
    <div class="otp_cell">
        <?php
        if (!empty($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="otp_form">
                <label for="otp">OTP:</label>
                <input type="text" id="otp" name="otp" required>
            </div>
            <div class="otp_form">
                <input type="submit" value="Verify OTP" class="btn-login">
            </div>
        </form>
    </div>
</body>

</html>