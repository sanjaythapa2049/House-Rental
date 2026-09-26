<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    $location = isset($_REQUEST['location']) ? trim($_REQUEST['location']) : '';
    $propertyType = isset($_REQUEST['propertyType']) ? $_REQUEST['propertyType'] : 'All';
    $remarks = isset($_REQUEST['remarks']) ? $_REQUEST['remarks'] : 'All';
    $locationSafe = mysqli_real_escape_string($dbConnect, $location);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Search Results</title>
</head>
<body>
    <div class="navContainer">
        <header class="guestHead">
            <div class="logo">
                <a href="guestMain.php"><img src="websiteImg/logo2.png" alt="Rooma Logo"></a>
            </div>

            <nav>
                <a href="guestMain.php" class="active">Home</a>

                <div class="dropdown">
                    <a href="#" class="dropBtn">For Rent ▼</a>
                    <div class="dropdownContent">
                        <a href="guestListingSearch.php?propertyType=Room&remarks=For%20Rent">Room</a>
                        <a href="guestListingSearch.php?propertyType=Flat%20%26%20Apartment&remarks=For%20Rent">Flat & Apartment</a>
                        <a href="guestListingSearch.php?propertyType=House&remarks=For%20Rent">House</a>
                        <a href="guestListingSearch.php?propertyType=Shutter%20%26%20Commercial%20space&remarks=For%20Rent">Shutter & Commercial space</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a href="#" class="dropBtn">For Sell ▼</a>
                    <div class="dropdownContent">
                        <a href="guestListingSearch.php?propertyType=Land&remarks=For%20Sell">Land</a>
                        <a href="guestListingSearch.php?propertyType=House&remarks=For%20Sell">House</a>
                        <a href="guestListingSearch.php?propertyType=Flat%20%26%20Apartment&remarks=For%20Sell">Flat & Apartment</a>
                        <a href="guestListingSearch.php?propertyType=Shop&remarks=For%20Sell">Shop</a>
                    </div>
                </div>
            </nav>

            <div class="profile">
                <a href="hostMain.php" class="switchMode">Switch to Host</a>
                <a href="" class="profileBtn"><img src="<?php echo htmlspecialchars(normalizeImagePath($uPp), ENT_QUOTES, 'UTF-8'); ?>" alt="User profile"></a>
            </div>
        </header>
    </div>

    <h2 class="explore">Search Results</h2>

    <div class="searchPropertyListings">
        <?php
            $search = "SELECT p.* 
            FROM properties p
            WHERE p.CityID IN (SELECT CityID FROM cities WHERE CityName LIKE '%$locationSafe%')
            AND p.PropertyStatus = 'Listed'";

            if ($propertyType != 'All' && !empty($propertyType)) {
                $propertyTypeSafe = mysqli_real_escape_string($dbConnect, $propertyType);
                $search .= " AND p.PropertyType = '$propertyTypeSafe'";
            }

            if ($remarks != 'All' && !empty($remarks)) {
                $remarksSafe = mysqli_real_escape_string($dbConnect, $remarks);
                $search .= " AND p.PropertyRemarks = '$remarksSafe'";
            }

            $searchResult = mysqli_query($dbConnect, $search);

while ($searchArray = mysqli_fetch_array($searchResult)) 
            {
                $ppID = $searchArray['PropertyID'];
                $ppImg1 = $searchArray['PropertyImage1'];
                $ppName = $searchArray['PropertyName'];
                $ppDes = $searchArray['PropertyDescription'];
                $ppPrice = $searchArray['PropertyPrice'];

                echo "<a href='guestListingDetails.php?ppID=$ppID' class='propertyLink'>";
                    echo "<div class='searchPropertyCard'>";
                        echo "<img src='" . htmlspecialchars(normalizeImagePath($ppImg1), ENT_QUOTES, 'UTF-8') . "'>";
                        echo "<div class='propertyInfo'>";
                            echo "<div class='propertyHeander'>";
                                echo "<span class='propertyName'> $ppName </span><br>";
                                echo '<span class="propertyDes">' . substr($ppDes, 0, 50) . '...</span>';
                            echo "</div>";
                            echo '<span class="propertyPrice">NRP ' . $ppPrice . '</span>';
                        echo '</div>';
                    echo '</div>';
                echo '</a>';
            }
        ?>
    </div>
</body>
</html>