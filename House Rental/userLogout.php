<?php
    include('dbConnect.php');
    session_start();

    /*Destroy all session data and redirect to login*/
    $_SESSION = array();
    session_destroy();
    echo "<script>window.location='userLogin.php'</script>";
    exit();
?>