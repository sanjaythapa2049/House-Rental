<?php
    include('dbConnect.php');
    session_start();

    if (isset($_SESSION['uID'])) 
    {
        $uID = $_SESSION['uID'];
        $uPp = $_SESSION['uPp'];
    }
    else
    {
        $uID = 0;
        $uPp = "profileImg/_photo.jpg";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Guest Dashboard</title>
</head>
<body>
    <div class="navContainer">
        <header class="guestHead">
            <div class="logo">
                <a href="guestMain.php"><img src="websiteImg/logo2.png" alt="Logo"></a>
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
                <a href="userLogout.php" class="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>
    </div>

    <div class="searchContainer">
        <form class="searchForm" action="guestListingSearch.php" method="POST">
            <div class="searchInput">
                <label>Location</label>
                <input type="text" name="location" placeholder="Search a city" required/>
            </div>
            <div class="searchInput">
                <label>Property Type</label>
                <select name="propertyType">
                    <option value="All">All</option>
                    <option value="Room">Room</option>
                    <option value="Flat & Apartment">Flat & Apartment</option>
                    <option value="House">House</option>
                    <option value="Shutter & Commercial space">Shutter & Commercial space</option>
                    <option value="Land">Land</option>
                    <option value="Shop">Shop</option>
                </select>
            </div>
            <div class="searchInput">
                <label>Remarks</label>
                <select name="remarks">
                    <option value="All">All</option>
                    <option value="For Rent">For Rent</option>
                    <option value="For Sell">For Sell</option>
                </select>
            </div>
            <button type="submit" class="searchButton">Search</button>
        </form>
    </div>

    <h2 class="explore">Explore New Properties</h2>

<div class="propertyListings">
        <?php
            $query = "SELECT * FROM properties
                      WHERE PropertyStatus = 'Listed'
                      LIMIT 12";
            $result = mysqli_query($dbConnect, $query);

            while ($array = mysqli_fetch_array($result)) 
            {
                $ppID = $array['PropertyID'];
                $ppImg1 = $array['PropertyImage1'];
                $ppName = $array['PropertyName'];
                $ppDes = $array['PropertyDescription'];
                $ppPrice = $array['PropertyPrice'];

                echo "<a href='guestListingDetails.php?ppID=$ppID' class='propertyLink'>";
                    echo "<div class='propertyCard'>";
                        echo "<img src='" . htmlspecialchars(normalizeImagePath($ppImg1), ENT_QUOTES, 'UTF-8') . "'>";
                        echo "<div class='propertyInfo'>";
                            echo "<div class='propertyHeader'>";
                                echo "<span class='propertyName'> $ppName </span><br>";
                                echo '<span class="propertyDes">' . substr($ppDes, 0, 50) . '...</span>';
                            echo "</div>";
                            echo '<span class="propertyPrice">NRP ' . $ppPrice . '</span>';
                        echo '</div>';
                    echo '</div>';
                echo '</a>';
            }
        ?>
    </div><br><br><br><br><br><br>

<?php include('footerWidget.php'); ?>
</body>
</html>