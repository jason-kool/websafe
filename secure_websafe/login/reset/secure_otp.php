<?php
include "../../init-timeout.php";
include "../../init-error.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}


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
    include "../../sql_con.php";

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
    <link rel="stylesheet" type="text/css" href="/design.css">
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