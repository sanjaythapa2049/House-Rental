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

    $pendingProperties = "SELECT p.*
                          FROM properties p
                          INNER JOIN propertyhosts ph ON ph.PropertyID = p.PropertyID
                          WHERE ph.UserID = $uID
                          AND ph.HostType = 'Host'
                          AND p.PropertyStatus = 'Pending'
                          ORDER BY p.PropertyListingDate DESC";
    $pendingPropertiesResult = mysqli_query($dbConnect, $pendingProperties);
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
                <a href="hostMain.php" class="active">Home</a>
                <a href="hostListing.php">Listings</a>

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
                <a href="" class="profileBtn"><img src="<?php echo $uPp ?>" alt=""></a>
                <a href="userLogout.php" class="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>
    </div>

    <div class="dashboardContainer">
        <main>
            <a href="hostListingAdd.php" class="listPropertyBtn">List your property</a><br>

            <div class="bookingSection">
                <h2>Your Pending Property Requests</h2>
                <div class="bookingTabs">
                    <span class="currentBooking">Pending requests awaiting admin approval</span>
                </div>

                <div id="pendingRequest" class="bookingContent active">
                    <?php
                        if (mysqli_num_rows($pendingPropertiesResult) > 0) {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Property</th>
                                        <th>Type</th>
                                        <th>Listing</th>
                                        <th>Price</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>";
                            while ($property = mysqli_fetch_assoc($pendingPropertiesResult)) {
                                $propertyId = (int)$property['PropertyID'];
                                echo "<tr>
                                        <td>" . htmlspecialchars($property['PropertyName']) . "</td>
                                        <td>" . htmlspecialchars($property['PropertyType']) . "</td>
                                        <td>" . htmlspecialchars($property['PropertyRemarks']) . "</td>
                                        <td>NRP " . htmlspecialchars($property['PropertyPrice']) . "</td>
                                        <td>" . htmlspecialchars($property['PropertyListingDate']) . "</td>
                                        <td>Pending admin approval</td>
                                        <td><a href='hostListingDetails.php?ppID=$propertyId'>View</a></td>
                                    </tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<div class='noBooking'>
                                    <div class='icon'>🏠</div>
                                    <p>You have no pending property requests.</p>
                                </div>";
                        }
                    ?>
                </div>

            </div>
        </main>
    </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php include('footerWidget.php'); ?>

    <script>
        window.onload = function() {
            var tabButtons = document.querySelectorAll('.tabBtn');
            var tabContents = document.querySelectorAll('.bookingContent');
            var dropdownBtn = document.querySelector('.dropBtn');
            var dropdownContent = document.querySelector('.dropdownContent');

            function handleTabClick(event) {
          
                tabButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });
                tabContents.forEach(function(content) {
                    content.classList.remove('active');
                });

                event.target.classList.add('active');

                var tabTarget = event.target.getAttribute('data-tab-target');
                document.getElementById(tabTarget).classList.add('active');
            }

            // tabs
            function toggleDropdown(event) {
                event.stopPropagation();
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            }

            function closeDropdown() {
                dropdownContent.style.display = 'none';
            }

            tabButtons.forEach(function(button) {
                button.addEventListener('click', handleTabClick);
            });

            dropdownBtn.addEventListener('click', toggleDropdown);

            window.addEventListener('click', closeDropdown);
        };
    </script>
</body>
</html>
