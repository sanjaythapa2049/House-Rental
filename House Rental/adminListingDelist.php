<?php 
	include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID']))
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='adminLogin.php'</script>";
        exit();
    }

	$ppID = (int)$_GET['ppID'];

	$deleteHosts = "DELETE FROM propertyhosts WHERE PropertyID = $ppID";
    $deleteProperty = "DELETE FROM properties WHERE PropertyID = $ppID";

	$queryHosts = mysqli_query($dbConnect, $deleteHosts);
    $queryProperty = mysqli_query($dbConnect, $deleteProperty);

	if ($queryHosts && $queryProperty)
	{
		echo "<script>window.alert('Listing delisted.')</script>";
		echo "<script>window.location='adminListingVerify.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with delisting the listing.</p>";
	}