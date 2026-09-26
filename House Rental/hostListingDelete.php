<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID']) || !isset($_GET['ppID'])) {
        header('Location: userLogin.php');
        exit;
    }

    $uID = (int)$_SESSION['uID'];
    $ppID = (int)$_GET['ppID'];
    $ownerCheck = mysqli_query($dbConnect, "SELECT PropertyID FROM propertyhosts WHERE PropertyID = $ppID AND UserID = $uID AND HostType = 'Host'");

    if (mysqli_num_rows($ownerCheck) !== 1) {
        http_response_code(403);
        exit('You can only delete your own property.');
    }

    mysqli_query($dbConnect, "DELETE FROM properties WHERE PropertyID = $ppID");

    header('Location: hostListing.php');
    exit;
?>