<?php
// CWE-613: Insufficient Session Expiration (Secure Version)
$timeout = 300; // timeout after five minutes
ini_set("session.gc_maxlifetime", $timeout);
ini_set("session.cookie_lifetime", $timeout);
session_start();
// Create a session that times out after set duration
$s_name = session_name();
if (isset($_COOKIE[$s_name])) {
    setcookie($s_name, $_COOKIE[$s_name], time() + $timeout, '/');
} else {
    if (session_destroy()) {
        setcookie("privilege", "", 0, "/");
        echo "
            <script>
                sessionStorage.clear();
                alert('Sorry, you have been inactive for too long. Please log in again.');
                window.location.href='/';
            </script>";
    }
}
?>