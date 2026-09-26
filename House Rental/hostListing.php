<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Host Dashboard</title>
</head>
<body>
    <div class="navContainer">
        <header class="hostHead">
            <div class="logo">
                <a href="hostMain.php"><img src="websiteImg/logo2.png" alt="Logo"></a>
            </div>

            <nav>
                <a href="hostMain.php">Home</a>
                <a href="hostListing.php" class="active">Listings</a>

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
                <a href="guestMain.php" class="switchMode">Switch to Guest</a>
                <a href="" class="profileBtn"><img src="<?php echo htmlspecialchars(normalizeImagePath($uPp), ENT_QUOTES, 'UTF-8'); ?>" alt="User profile"></a>
            </div>
        </header>
    </div>

    <div class="hostListingSection">
        <h2>Listed Properties</h2>
        <div class="hostPropertyListings">
        <?php
            $query = "SELECT p.*, ph.*
                      FROM properties p, propertyhosts ph
                      WHERE p.PropertyID = ph.PropertyID
                      AND ph.UserID = $uID
                      ";
            $result = mysqli_query($dbConnect, $query);
            $resultCount = mysqli_num_rows($result);

            if ($resultCount > 0)
            {
while ($array = mysqli_fetch_array($result)) 
                {
                    $ppID = $array['PropertyID'];
                    $ppImg1 = $array['PropertyImage1'];
                    $ppName = $array['PropertyName'];
                    $ppDes = $array['PropertyDescription'];
                    $ppPrice = $array['PropertyPrice'];

                    echo "<a href='hostListingDetails.php?ppID=$ppID' class='hostPropertyLink'>";
                        echo "<div class='hostPropertyCard'>";
                            echo "<img src='" . htmlspecialchars(normalizeImagePath($ppImg1), ENT_QUOTES, 'UTF-8') . "'>";
                            echo "<div class='hostPropertyInfo'>";
                                echo "<div class='HostPropertyHeander'>";
                                    echo "<span class='hostPropertyName'> $ppName </span><br>";
                                    echo '<span class="hostPropertyDes">' . $ppDes . '...</span>';
                                echo "</div>";
                                echo '<span class="hostPropertyPrice">NRP ' . $ppPrice . '</span>';
                            echo '</div>';
                        echo '</div>';
                    echo '</a>';
                }
            }

            else {
                echo "<div class='noListing'>
                        <div class='icon'>🏠</div>
                        <p>You have no properties listed</p>
                    </div>";
            }
        ?>
        </div>
    </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <?php include('footerWidget.php'); ?>
</body>
</html>
