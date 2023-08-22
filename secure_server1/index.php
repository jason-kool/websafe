<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/sex.css">
    <title>Manage Users</title>
</head>
<body>
    <div id="navbar"><h1>Websafe external user management</h1></div>

    <div class="admincontainer">
    
        <?php
            $con = mysqli_connect("secure_database","Lottie", "Ad0r@ble", "websafe");

            if (!$con) {
                die("Failed to connect " . mysqli_connect_errno());
            }


            $query = $con->prepare("SELECT * FROM `users`");
            // SELECT everything FROM a table called `products`

            // user_id username password email profilepicture privilege
            if ($query->execute()) {
                $query->bind_result($userid, $username, $password, $email, $profilepic, $privilege);
                $query->store_result();

                echo '<table border="1" align="center">';
                echo '<thead><tr><th colspan="7">Current list of users</th></tr><tr><th><b>User ID</b></th><th><b>User Name</b></th><th><b>Password</b></th><th><b>Email</b></th><th><b>Profile Picture</b></th><th><b>Privilege</b></th></tr></thead><tbody>';

                $open = "<td>";
                $close = "</td>";
                
                while ($query->fetch()) {
                    echo '<tr><td>'.$userid.'</td><td>'.$username.'</td><td>'.$password.'</td><td>'.$email.'</td><td>'.$profilepic.'</td><td>'.$privilege.'</td><td><a href="deleteUser.php?user_id='.$userid.'">Delete</a></td><td><a href="toggleAdmin.php?user_id='.$userid.'&priv='.$privilege.'">Toggle admin</a></td></tr>';
                }

                echo '</tbody></table>';
                
            } else {
                echo "Error executing query.";
            }
        ?>

        <br><br>
        
        <form action="addUser.php" method="post">
            <table border="1" align=center>
                <thead>
                    <tr>
                        <td colspan="2">
                            <b>Insert a new Admin</b>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Username:</td>
                        <td><input type="text" name="username"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="text" name="password"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="text" name="email"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="form_submit" value="Submit">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>



</body>
</body>
</html>