<?php
// CWE-640: Weak Password Recovery Mechanism for Forgotten Password
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';
require_once '../../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include "../../init-timeout.php";
include "../../init-error.php";
include "../../sql_con.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}


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
        $email = $_POST["email"];
        $query = $con->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $query->bind_param('s', $email);
        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows == 1) {
                $otp = generateOTP();
                $insertQuery = $con->prepare("INSERT INTO `otp_requests` (`email`, `otp`, `created_at`) VALUES (?, ?, NOW())");
                $insertQuery->bind_param('ss', $email, $otp);

                if ($insertQuery->execute()) {
                    $mail = new PHPMailer();

                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'websafe.shopping@gmail.com';
                    $mail->Password = 'tugyqufckwniwozq';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Set the recipient's email address
                    $mail->addAddress($email);
                    $emailContent = "Hi, this is the Websafe support. Please enter the OTP for the password reset:<br><br>";
                    $emailContent .= "OTP: " . $otp . "<br><br>";
                    $emailContent .= "To reset your password, click <a href='http://localhost:9000/secure_otp.php'>here</a>.<br><br>";
                    $emailContent .= "If you didn't request this password reset, please ignore this email.";

                    // Set the email content
                    $mail->isHTML(true);
                    $mail->Subject = "WebSafe Shopping Reset Password";
                    $mail->Body = $emailContent;

                    // Send the email
                    if ($mail->send()) {
                        $_SESSION["reset_email"] = $email;
                        $_SESSION["reset_otp"] = $otp;
                        header("Location: secure_otp.php");
                        exit();
                    } else {
                        $error = "Failed to send the OTP. Please try again later.";
                    }
                } else {
                    $error = "Error storing the OTP. Please try again later.";
                }
            } else {
                $error = "Invalid email. Please enter a valid email address.";
            }
        }
    }
}

$con->close();

function generateOTP()
{
    $digits = 6;
    $otp = "";
    for ($i = 0; $i < $digits; $i++) {
        $otp .= mt_rand(0, 9);
    }
    return $otp;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="/design.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <?php include "../../navbar.php"; ?>



    <div class="container">
        <div class="login_card">
            <h1>RESET YOUR PASSWORD</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="email" placeholder="Email" name="email" required>
                <div class="g-recaptcha" data-sitekey="6LdgKDAnAAAAADK49r3eybJWd9kyoE32PIO-ixkt"></div>
                <input type="submit" value="Submit" class="button" name="form_submit">
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