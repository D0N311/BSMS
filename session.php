<?php
 session_start();
 

 if ($_SESSION['username'] == 'invalid' || empty($_SESSION['username'])) {
    $_SESSION['username'] = 'invalid';

    unset($_SESSION['username']);

    echo "<script>window.location.href = 'login.php'</script>";
 }

?>