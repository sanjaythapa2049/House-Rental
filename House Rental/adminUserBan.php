<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID']) || !isset($_GET['uID'])) {
        header('Location: adminLogin.php');
        exit;
    }

    $uID = (int)$_GET['uID'];
    mysqli_query($dbConnect, "UPDATE users SET AccountStatus = 'Banned', GuestVerificationStatus = NULL, HostVerificationStatus = NULL WHERE UserID = $uID");
    header('Location: adminVerifyRequest.php');
    exit;
?>
