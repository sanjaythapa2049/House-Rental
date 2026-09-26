<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    if (!isset($_GET['ppID'])) {
        echo "<script>window.alert('Invalid property selection.')</script>";
        echo "<script>window.location='guestMain.php'</script>";
        exit();
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];
    $ppID = $_GET['ppID'];

    $listingQuery = "SELECT pp.*, u.UserName, u.UserPp, ph.HostType, c.CityName, pp.HostPhone1, pp.HostPhone2, pp.HostEmail
                     FROM properties pp, propertyhosts ph, users u, cities c
                     WHERE pp.PropertyID = $ppID 
                     AND pp.PropertyID = ph.PropertyID
                     AND u.UserID = ph.UserID
                     AND pp.CityID = c.CityID
                     AND ph.HostType = 'Host'
                     AND pp.PropertyStatus = 'Listed'";

    $listingResult = mysqli_query($dbConnect, $listingQuery);
    $count = mysqli_num_rows($listingResult);

    if ($count == 0) 
    {
        echo "<script>window.alert('Property not found.')</script>";
        echo "<script>window.location='guestMain.php'</script>";
    }

    $listingArray = mysqli_fetch_array($listingResult);

    $ppName = $listingArray["PropertyName"];
    $ppAddress = $listingArray["PropertyAddress"];
    $ppCoordinates = $listingArray["PropertyCoordinates"];
    $ppImg1 = $listingArray["PropertyImage1"];
    $ppImg2 = $listingArray["PropertyImage2"];
    $ppImg3 = $listingArray["PropertyImage3"];
    $ppDes = $listingArray["PropertyDescription"];
    $ppRooms = $listingArray["PropertyRooms"];
    $ppMaxGuests = $listingArray["PropertyMaxGuest"];
    $ppListingDate = $listingArray["PropertyListingDate"];
    $ppStatus = $listingArray["PropertyStatus"];
    $ppPrice = $listingArray["PropertyPrice"];
    $cName = $listingArray["CityName"];
    $ppType = $listingArray["PropertyType"];
    $ppRemarks = $listingArray["PropertyRemarks"];
    $uPp = $listingArray["UserPp"];
    $uName = $listingArray["UserName"];
    $hostPhone1 = $listingArray["HostPhone1"];
    $hostPhone2 = $listingArray["HostPhone2"];
    $hostEmail = $listingArray["HostEmail"];

    $bookedDates = array();

    $reviewResult = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Listing Details</title>
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

    <div class="propertyDetails">
        <div class="propertyHeader">
            <h1><?php echo $ppName ?></h1>
            <p><?php echo $ppAddress ?></p>
            <p>Location: <?php echo $cName ?></p>
        </div>

        <div class="propertyImages">
            <img src="<?php echo htmlspecialchars(normalizeImagePath($ppImg1), ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo htmlspecialchars(normalizeImagePath($ppImg2), ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo htmlspecialchars(normalizeImagePath($ppImg3), ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="propertyInfo">
            <div class="propertyDescription">
                <h2>About this place</h2>
                <p><?php echo $ppDes ?></p>

                <h3>Property Details</h3>
                <p><strong>Rooms:</strong> <?php echo $ppRooms ?></p>
                <p><strong>Property type:</strong> <?php echo $ppType ?></p>
                <p><strong>Listing:</strong> <?php echo $ppRemarks ?></p>
                <p><strong>Listed on:</strong> <?php echo $ppListingDate ?></p>
                <p><strong>Price:</strong> NRP <?php echo $ppPrice ?></p><br>
                
                <h3>Location</h3>
                <p><strong>City:</strong> <?php echo $cName ?></p>
                
                <p><strong>Map</strong></p>
                <?php
                    $mapCoordinates = explode(',', $ppCoordinates);
                    $lat = trim($mapCoordinates[0]);
                    $long = trim($mapCoordinates[1]);
                    $mapUrl = "https://www.google.com/maps?q={$lat},{$long}&output=embed";
                    $navigationUrl = "https://www.google.com/maps/dir/?api=1&destination={$lat},{$long}";
                ?>
                <iframe src="<?php echo $mapUrl; ?>" width="161%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <p><a href="<?php echo $navigationUrl; ?>" target="_blank" rel="noopener">Navigate with Google Maps</a></p>

                <h3>Host Information</h3>
                <div class="hostInfo">
                    <img src="<?php echo htmlspecialchars(normalizeImagePath($uPp), ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="hostDetails">
                        <h4>Owner <?php echo $uName ?>
                        <div class="hostVerified">
                            <span>✓</span> Verified
                        </div></h4>
                    </div>
                </div>
            </div>

            <div class="bookingPanel">
                <h2>Contact the owner for this listing</h2>
                <div class="hostContactInfo">
                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($uName) ?></p>
                    <p><strong>Phone (compulsory):</strong> <?php echo htmlspecialchars($hostPhone1) ?: 'Not provided' ?></p>
                    <?php if ($hostPhone2): ?>
                        <p><strong>Phone (optional):</strong> <?php echo htmlspecialchars($hostPhone2) ?></p>
                    <?php endif; ?>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($hostEmail) ?: 'Not provided' ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="reviewContainer">
        <div class="reviewBoxes">
            <p>No reviews available.</p>
        </div>
    </div><br><br><br><br><br><br><br><br>

<?php include('footerWidget.php'); ?>

    <script>
        var bookedDates = [];
        var todayDate = new Date().toISOString().split("T")[0];

        var checkInDate = document.getElementById("checkInDate");
        var checkOutDate = document.getElementById("checkOutDate");

        /*Set minimum date*/
        checkInDate.min = todayDate;
        checkOutDate.min = todayDate;

        /*Not avaliable booked dates*/
        function disableBookedDates(dateInput) {
            dateInput.addEventListener("input", function() {
                var selectedDate = new Date(this.value);
                if (bookedDates.includes(selectedDate.toISOString().split("T")[0])) {
                    this.value = "";
                    alert("This date is not available. Please choose another date.");
                }
            });
        }
        disableBookedDates(checkInDate);
        disableBookedDates(checkOutDate);

        /*Set checkout date after checkin date*/
        checkInDate.addEventListener("change", function() {
            var minCheckOutDate = new Date(this.value);
            minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
            checkOutDate.min = minCheckOutDate.toISOString().split("T")[0];
            checkOutDate.value = "";
        });

        /*Date range*/
        function isDateRangeAvailable(start, end) {
            let current = new Date(start);
            while (current <= end) {
                if (bookedDates.includes(current.toISOString().split("T")[0])) {
                    return false;
                }
                current.setDate(current.getDate() + 1);
            }
            return true;
        }

        /*unavaliable date range*/
        checkOutDate.addEventListener("change", function() {
            var start = new Date(checkInDate.value);
            var end = new Date(this.value);
            
            if (!isDateRangeAvailable(start, end)) {
                this.value = "";
                alert("Some dates in this range are not available. Please choose different dates.");
            }
        });
    </script>
</body>
</html>