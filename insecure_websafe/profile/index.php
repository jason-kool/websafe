<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../");
}

include "../sql_con.php";


$errorMsg = "";
$profileUpdated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION["user_id"];
    $newUsername = $_POST["username"];
    $newPassword = $_POST["password"];
    $newEmail = $_POST["email"];
    $oldPassword = $_POST["oldPassword"];

    $verificationQuery = $con->prepare("SELECT password FROM `users` WHERE user_id = ?");
    $verificationQuery->bind_param('i', $userId);
    $verificationQuery->execute();
    $verificationQuery->bind_result($setPassword);
    $verificationQuery->fetch();
    $verificationQuery->close();

    if ($setPassword == base64_encode($oldPassword)) {
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
                $updateUsername = "Username has been updated";
            } elseif (!empty($newUsername)) {
                $updateUsername = "ONLY ALPHABETS, NUMBERS AND UNDERSCORES";
            }

            if ($newPassword !== "") {
                // CWE-261: Weak Encoding for Password
                // CWE-521: Weak Password Requirements
                $updatePasswordQuery = $con->prepare("UPDATE `users` SET `password` = ? WHERE `user_id` = ?");
                $updatePasswordQuery->bind_param('si', base64_encode($newPassword), $userId);
                $updatePasswordQuery->execute();
                $updatePasswordQuery->close();
                $updatePassword = "Password has been updated";
            } elseif (!empty($newPassword)) {
                $updatePassword = "AT LEAST 8 CHARACTERS, CAPITAL LETTER AND SMALL LETTER";
            }
            if ($newEmail !== "") {
                $updateEmailQuery = $con->prepare("UPDATE `users` SET `email` = ? WHERE `user_id` = ?");
                $updateEmailQuery->bind_param('si', $newEmail, $userId);
                $updateEmailQuery->execute();
                $updateEmailQuery->close();
                $updateEmail = "Email has been updated";
            } elseif (!empty($newEmail)) {
                $updateEmail = "ENSURE EMAIL IS PROPERLY FORMATTED";
            }
        }
    } else {
        $errorMsg = "Profile update failed. Your old password does not match";
    }

    // Handle profile picture upload
    // CWE-434: Unrestricted Upload of File with Dangerous Type
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
$userId = $_SESSION['user_id']; 
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
    <link rel="stylesheet" type="text/css" href="/design.css">
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
            <form class="profile_form" method="POST" action="."
                enctype="multipart/form-data">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div>
                                    <label for="username">Username:
                                        <?php
                                        if (!empty($updateUsername)) {
                                            echo '<span align="center" class="success">' . $updateUsername . '</span>';
                                        }
                                        ?>
                                    </label>
                                    <br>
                                    <input style="border-radius: 7px;" type="text" id="username" name="username"
                                        value="<?php echo $username; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="password">Change Password:
                                        <?php
                                        if (!empty($updatePassword)) {
                                            echo '<span align="center" class="success">' . $updatePassword . '</span>';
                                        }
                                        ?>
                                    </label>
                                    <br>
                                    <input style="border-radius: 7px;" type="password" id="password" name="password"
                                        placeholder="New Password">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="email">Email:
                                        <?php
                                        if (!empty($updateEmail)) {
                                            echo '<span align="center" class="success">' . $updateEmail . '</span>';
                                        }
                                        ?>
                                    </label>
                                    <br>
                                    <input style="border-radius: 7px;" type="email" id="email" name="email"
                                        value="<?php echo $email; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <label for="profile_picture">Profile Picture:
                                        <?php
                                        if (!empty($updatePic)) {
                                            echo '<span align="center" class="success">' . $updatePic . '</span>';
                                        }
                                        ?>
                                    </label>
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
                                    <input style="border-radius: 7px;" type="password" id="oldPassword"
                                        name="oldPassword" placeholder="Old Password" required>
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