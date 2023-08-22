<?php
$con = mysqli_connect("secure_database","Lottie", "Ad0r@ble", "websafe");

if (!$con) {
    die('Failed to connect: ' . mysqli_connect_errno()); 
}

if(isset($_POST["form_submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $query = $con->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $query->bind_param('ss', $username, $email);  
    $result = $query->execute(); 
    $result = $query->get_result();
    if ($result) {
            
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if ($row['username'] === $username) {           //if user enters an existing username, decline request
                echo "
                    <script>
                        alert('The username that you have entered is already taken!');
                        window.location.href='/'
                    </script>";
                    die();
            }
            elseif($row['email'] === $email){              //if user enters an existing email, decline request
                echo "
                    <script>
                        alert('The email that you have entered is already registered!');
                        window.location.href='/'
                    </script>";
                    die();
            }
        
        }
            
    }


    $query = $con->prepare('INSERT INTO `users` (`username`, `password`, `email`, `privilege`) VALUES (?,?,?,"admin")');

    $query->bind_param('sss', $username, $password, $email); 

    if ($query->execute()) {
        echo "Query executed.";
        echo "<script> window.location.href='/' </script>";
    } else {
        echo "Error executing query.";
    }

    $con->close(); 
    }



?>