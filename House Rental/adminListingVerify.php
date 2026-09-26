<?php 
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='adminLogin.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>Document</title>
</head>
<body>
    <div class="homeSection">
        <a href="adminMain.php" class="homeBtn"><i class="fas fa-home"></i> Home</a>
    </div>
    <div class="tabs">
		<button class="tabButton" data-tab-target="tab1">Listing verification requests</button>
		<button class="tabButton" data-tab-target="tab2">Verified listings</button>

		<div id="tab1" class="tabContent">
			<h2 class="listingTitle scroll">Listing Verifications</h2><br>
            <div>
                <table class="listingTable">
                    <tr>
                        <th>Property ID</th>
                        <th>Property name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Image</th>
                        <th>Address</th>
                        <th>Coordinates</th>
                        <th>Rooms</th>
                        <th>Description</th>
                        <th>Listing Date</th>
                        <th>Status</th>
                        <th>Price in NRs</th>
                        <th>Options</th>
                    </tr>

                    <?php 
                        $propertySelect = "SELECT p.*, c.CityName
                                           FROM properties p
                                           LEFT JOIN cities c ON c.CityID = p.CityID
                                           WHERE p.PropertyStatus = 'Pending'
                                           ORDER BY p.PropertyListingDate DESC";
                        $propertyResult = mysqli_query($dbConnect, $propertySelect);

                        while ($array = mysqli_fetch_array($propertyResult)) 
                        {
                            $ppID = $array['PropertyID'];
                            $ppName = $array['PropertyName'];

                            $cName = 'Nepal';

                            $ciName = $array['CityName'] ?: 'Unknown city';

                            $ppImage = normalizeImagePath($array['PropertyImage1']);
                            $ppAddress = $array['PropertyAddress'];
                            $ppCords = $array['PropertyCoordinates'];
                            $ppRooms = $array['PropertyRooms'];
                            $ppDes = $array['PropertyDescription'];
                            $ppListingDate = $array['PropertyListingDate'];
                            $ppStatus = $array['PropertyStatus'];
                            $ppPrice = $array['PropertyPrice'];
                            
                            echo "<tr>";
                                echo "<td>$ppID</td>";
                                echo "<td>$ppName</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$ciName</td>";
                                echo "<td><img src='$ppImage' width='100px' height='100px'></td>";
                                echo "<td>$ppAddress</td>";
                                echo "<td>$ppCords</td>";
                                echo "<td>$ppRooms</td>";
                                echo "<td>$ppDes</td>";
                                echo "<td>$ppListingDate</td>";
                                echo "<td>$ppStatus</td>";
                                echo "<td>NRs $ppPrice</td>";
                                echo "<td>
                                <a href='adminListingVerifyAccept.php?ppID=$ppID'>Accept</a>|<br>
                                <a href='adminListingVerifyDeny.php?ppID=$ppID'>Deny</a>|<br>
                                </td>";
                            echo "</tr>";
                        }  
                    ?>
                </table>
            </div><br>
        </div>

        <div id="tab2" class="tabContent">
			<h2 class="listingTitle scroll">Verified Listings</h2><br>
            <div>
                <table class="listingTable">
                    <tr>
                        <th>Property ID</th>
                        <th>Property name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Image</th>
                        <th>Address</th>
                        <th>Coordinates</th>
                        <th>Rooms</th>
                        <th>Description</th>
                        <th>Listing Date</th>
                        <th>Status</th>
                        <th>Price in NRs</th>
                        <th>Admin ID</th>
                        <th>Options</th>
                    </tr>

                    <?php 
                        $propertySelect = "SELECT * FROM properties 
                                           WHERE PropertyStatus = 'Listed'";
                        $propertyResult = mysqli_query($dbConnect, $propertySelect);

                        while ($array = mysqli_fetch_array($propertyResult)) 
                        {
                            $ppID = $array['PropertyID'];
                            $ppName = $array['PropertyName'];
                            $ciID = $array['CityID'];

                            $cName = 'Nepal';

                            $ciNameSelect = "SELECT CityName FROM cities
                                             WHERE CityID = $ciID";
                            $ciNameQuery = mysqli_query($dbConnect, $ciNameSelect);
                            $ciNameArray = mysqli_fetch_array($ciNameQuery);
                            $ciName = $ciNameArray["CityName"];

                            $ppImage = normalizeImagePath($array['PropertyImage1']);
                            $ppAddress = $array['PropertyAddress'];
                            $ppCords = $array['PropertyCoordinates'];
                            $ppRooms = $array['PropertyRooms'];
                            $ppDes = $array['PropertyDescription'];
                            $ppListingDate = $array['PropertyListingDate'];
                            $ppStatus = $array['PropertyStatus'];
                            $ppPrice = $array['PropertyPrice'];
                            
                            $aID = $array['AdminID'];

                            echo "<tr>";
                                echo "<td>$ppID</td>";
                                echo "<td>$ppName</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$ciName</td>";
                                echo "<td><img src='$ppImage' width='100px' height='100px'></td>";
                                echo "<td>$ppAddress</td>";
                                echo "<td>$ppCords</td>";
                                echo "<td>$ppRooms</td>";
                                echo "<td>$ppDes</td>";
                                echo "<td>$ppListingDate</td>";
                                echo "<td>$ppStatus</td>";
                                echo "<td>NRs $ppPrice</td>";
                                echo "<td>$aID</td>";
                                echo "<td>
                                <a href='adminListingDelist.php?ppID=$ppID'>Delist</a>|<br>
                                </td>";
                            echo "</tr>";
                        }  
                    ?>
                </table>
            </div><br>
        </div>
    </div>

    <script>
		document.addEventListener('DOMContentLoaded', function() {
		    var tabButtons = document.querySelectorAll('.tabButton');
		    var tabContents = document.querySelectorAll('.tabContent');

		    tabButtons.forEach((button) => {
			    button.addEventListener('click', () => {
			    var target = button.dataset.tabTarget;
			    tabButtons.forEach((btn) => btn.classList.remove('active'));
			    button.classList.add('active');
			    tabContents.forEach((content) => content.classList.remove('active'));
			    document.getElementById(target).classList.add('active');
		        });
		    });

		    tabButtons[0].click();
		});
	</script>
</body>
</html>