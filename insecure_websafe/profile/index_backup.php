<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../");
}

$con = mysqli_connect("insecure_database", "Lottie", "Ad0r@ble", "websafe");


$errorMsg = "";
$profileUpdated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION["user_id"];
    $newUsername = $_POST["username"];
    $newPassword = $_POST["password"];
    $newEmail = $_POST["email"];
    $oldPassword = $_POST["oldPassword"];

    // CWE-261: Weak Encoding for Password (Secure Version)
    $timeTarget = 0.05;
    $cost = 8; // minimum number of operations necessary to compute the password hash
    do {
        $cost++;
        $start = microtime(true);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ["cost" => $cost]); // salted hash function (salt is randomly generated)
        $end = microtime(true);
    } while (($end - $start) < $timeTarget); // as long as the code has been running for less than 50 milliseconds, the cost increases by one

    $verificationQuery = $con->prepare("SELECT password FROM `users` WHERE user_id = ?");
    $verificationQuery->bind_param('i', $userId);
    $verificationQuery->execute();
    $verificationQuery->bind_result($setPassword);
    $verificationQuery->fetch();
    $verificationQuery->close();

    if (password_verify($oldPassword, $setPassword)) {
        // Check if the new username is unique
        $checkUsernameQuery = $con->prepare("SELECT * FROM `users` WHERE `username` = ? AND `user_id` != ?");
        $checkUsernameQuery->bind_param('si', $newUsername, $userId);
        $checkUsernameQuery->execute();
        $usernameResult = $checkUsernameQuery->get_result();

        // Check if the new email is unique
        $checkEmailQuery = $con->prepare("SELECT * FROM `users` WHERE `email` = ? AND `user_id` != ?");
        $checkEmailQuery->bind_param('si', $newEmail, $userId);
        $checkEmailQuery->execute();
        $emailResult = $checkEmailQuery->get_result();

        if ($usernameResult->num_rows > 0) {
            $errorMsg = "Username already exists.";
        } elseif ($emailResult->num_rows > 0) {
            $errorMsg = "Email already exists.";
        } else {

            if ($newUsername !== "") {
                $updateNameQuery = $con->prepare("UPDATE `users` SET `username` = ? WHERE `user_id` = ?");
                $updateNameQuery->bind_param('si', $newUsername, $userId);
                $updateNameQuery->execute();
                $updateNameQuery->close();
                $profileUpdated = true;
                if ($newPassword !== "") {
                    $updatePasswordQuery = $con->prepare("UPDATE `users` SET `password` = ? WHERE `user_id` = ?");
                    $updatePasswordQuery->bind_param('si', $hashedPassword, $userId);
                    $updatePasswordQuery->execute();
                    $updatePasswordQuery->close();
                    $profileUpdated = true;
                    if ($newEmail !== "") {
                        $updateEmailQuery = $con->prepare("UPDATE `users` SET `email` = ? WHERE `user_id` = ?");
                        $updateEmailQuery->bind_param('si', $newEmail, $userId);
                        $updateEmailQuery->execute();
                        $updateEmailQuery->close();
                        $profileUpdated = true;
                    }
                }
            }
        }
    } else {
        $errorMsg = "Profile update failed. Your old password does not match";
    }

    // Handle profile picture upload
    $profilePicture = $_FILES["profile_picture"];
    if (!empty($profilePicture)) {
        $fileName = $profilePicture["name"];
        $fileTmpName = $profilePicture["tmp_name"];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $newUsername . "_profile_pic." . $fileExtension;
        $fileDestination = "../iamges/user_profiles/" . $newFileName; // path to directory relative from current position

        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            $updatePictureQuery = $con->prepare("UPDATE `users` SET `profilepicture` = ? WHERE `user_id` = ?");
            $updatePictureQuery->bind_param('si', $newFileName, $userId);
            $updatePictureQuery->execute();
            $updatePictureQuery->close();

            $profileUpdated = true;
        }
    }
}
if ($profileUpdated) {
    $errorMsg = "Profile successfully updated.";
}


// Retrieve user data
$userId = $_SESSION['user_id']; // Replace with the actual user ID
$userQuery = $con->prepare("SELECT `user_id`, `username`, `password`, `email`, `profilepicture` FROM `users` WHERE `user_id` = ?");
$userQuery->bind_param('i', $userId);
$userQuery->execute();
$userQuery->bind_result($userId, $username, $password, $email, $profilePicture);
$userQuery->fetch();
$userQuery->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="/sex.css">
</head>

<body>

    <?php include "../navbar.php"; ?>

    <div class="container">
        <h1 style="color:white">Profile</h1>
        <div class="profile_cell">
            <div class="profile-picture">
                <?php
                if (!empty($profilePicture)) {
                    // for HTML elements, path can be from document root (/)
                    echo '<img src="/iamges/user_profiles/' . $profilePicture . '" alt="Profile Picture">';
                } else {
                    echo '<img src="/iamges/user_profiles/profile.jpg" alt="Default Profile Picture">';
                }
                ?>
            </div>
            <form class="profile_form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div>
                                    <label for="username">Username:</label>
                                    <br>
                                    <input style="border-radius: 7px;" type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="password">Password:</label>
                                    <br>
                                    <input style="border-radius: 7px;" type="password" id="password" name="password" placeholder="Password" required>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="email">Email:</label>
                                    <br>
                                    <input style="border-radius: 7px;" type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="profile_picture">Profile Picture:</label>
                                    <br>
                                    <input type="file" id="profile_picture" name="profile_picture">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="oldPassword"> Password verification: </label>
                                    <br>
                                    <input style="border-radius: 7px;" type="password" id="oldPassword" name="oldPassword" placeholder="Old Password" required>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <button class="profile_update" type="submit">Update Profile</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>

        </div>
        </form>

    </div>
    <?php
    if (!empty($errorMsg)) {
        echo '<p align="center" class="success">' . $errorMsg . '</p>';
    }
    ?>
    </div>
</body>

</html>