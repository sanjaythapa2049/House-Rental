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

	if (isset($_POST['btnList'])) 
	{
		$ppName = $_POST['txtppName'];
		$ppAddress = $_POST['txtppAddress'];
		$ppCord = $_POST['txtppCoordinates'];
		$ppRoom = $_POST['txtppRoom'];
        $ppType = $_POST['cboPropertyType'];
        $ppRemarks = $_POST['cboPropertyRemarks'];
        $ppMaxGuest = 0;
        $ppDes = $_POST['txtppDescription'];
        $ppListingDate = date('Y-m-d');
        $ppStatus = 'Pending';
        $ppPrice = $_POST['txtppPrice'];
        $ciIDIn = $_POST['cbociID'];
        $hostPhone1 = $_POST['txtHostPhone1'];
        $hostPhone2 = $_POST['txtHostPhone2'];
        $hostEmail = $_POST['txtHostEmail'];
        $hostType = 'Host';

        /*Property image 1 upload*/
		$pp1 = $_FILES['imgpp1']['name'];
		$folder = "profileImg/";
		$pp1FileName = $folder."_".$pp1;
		$copy = copy($_FILES['imgpp1']['tmp_name'], $pp1FileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload image.</p>";
		}

        /*Property image 2 upload*/
        $pp2 = $_FILES['imgpp2']['name'];
		$folder = "profileImg/";
		$pp2FileName = $folder."_".$pp2;
		$copy = copy($_FILES['imgpp2']['tmp_name'], $pp2FileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload image.</p>";
		}

        /*Property image 3 upload*/
		$pp3 = $_FILES['imgpp3']['name'];
		$folder = "profileImg/";
		$pp3FileName = $folder."_".$pp3;
		$copy = copy($_FILES['imgpp3']['tmp_name'], $pp3FileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload image.</p>";
		}

        $propertyInsert = "INSERT INTO properties (PropertyName, PropertyImage1, PropertyImage2, PropertyImage3, PropertyAddress, PropertyCoordinates, PropertyRooms, PropertyType, PropertyRemarks, PropertyMaxGuest, PropertyDescription, PropertyListingDate, PropertyStatus, PropertyPrice, CityID, HostPhone1, HostPhone2, HostEmail)
               VALUES('$ppName','$pp1FileName', '$pp2FileName', '$pp3FileName', '$ppAddress', '$ppCord', '$ppRoom', '$ppType', '$ppRemarks', '$ppMaxGuest', '$ppDes', '$ppListingDate', '$ppStatus', '$ppPrice', '$ciIDIn', '$hostPhone1', '$hostPhone2', '$hostEmail')";

		$propertyQuery = mysqli_query($dbConnect, $propertyInsert);

        if ($propertyQuery) 
        {
            $ppID = mysqli_insert_id($dbConnect);

            $hostInsert = "INSERT INTO propertyhosts (UserID, PropertyID, HostType)
                           VALUES ('$uID', '$ppID', '$hostType')";
            $hostQuery = mysqli_query($dbConnect, $hostInsert);

            echo "<script>window.alert('Property listing has been submitted. We will reach back to you once your listing is verified.')</script>";
            echo "<script>window.location='hostListing.php'</script>";
        }
	}

    if (isset($_GET['action'])) 
    {
        if ($_GET['action'] == 'getCities') 
        {
            $citySelect = "SELECT * FROM cities";
            $cityResult = mysqli_query($dbConnect, $citySelect);

                        echo "<select name='cbociID' class='registerInfo' required>";
                        echo "<option value=''>Select a city</option>";
            while ($ciArray = mysqli_fetch_array($cityResult)) 
            { 
                $ciID = $ciArray['CityID'];
                $ciName = $ciArray['CityName'];
                echo "<option value='$ciID'>$ciName</option>";
            }
            echo "</select>";
            exit;
        }

    }
 ?>

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="roomaStyle.css">
	<title></title>
</head>

<body id="RegisterBody">
	<div id="registerContainer">
	    <form id="Register" action="hostListingAdd.php" method="POST" enctype="multipart/form-data">

            <label>City</label>
            <div id="cityDropdown">
                <?php
                    $citySelect = "SELECT * FROM cities ORDER BY CityName";
                    $cityResult = mysqli_query($dbConnect, $citySelect);
                    echo "<select name='cbociID' class='registerInfo' required>";
                    echo "<option value=''>Select a city</option>";
                    while ($city = mysqli_fetch_assoc($cityResult)) {
                        echo "<option value='{$city['CityID']}'>{$city['CityName']}</option>";
                    }
                    echo "</select>";
                ?>
            </div><br>

            <label>Property image 1</label>
            <input class="registerInfo" type="file" name="imgpp1" required/><br><br>
            <label>Property image 2</label>
		 	<input class="registerInfo" type="file" name="imgpp2" required/><br><br>
            <label>Property image 3</label>
		 	<input class="registerInfo" type="file" name="imgpp3" required/><br><br>
            <label>Property name</label>
			<input class="registerInfo" type="text" name="txtppName" placeholder="Listing's name" required><br>
            <label>Address</label>
			<input class="registerInfo" type="text" name="txtppAddress" placeholder="Listing's address" required><br>
            <label>Map coordinates</label>
			<input class="registerInfo" type="text" name="txtppCoordinates" id="coordinates" placeholder="Latitude, longitude" required>
            <button type="button" class="registerSubmit" id="useLocation">Use my current location</button>
            <a id="mapLink" href="https://www.google.com/maps" target="_blank" rel="noopener">Open coordinates in Google Maps</a><br><br>
			<label>Rooms</label>
			<input class="registerInfo" type="text" name="txtppRoom" placeholder="The available rooms" required><br>
            <label>Property type</label>
            <select name="cboPropertyType" class="registerInfo" required>
                <option value="">Select property type</option>
                <option value="Room">Room</option>
                <option value="Flat & Apartment">Flat & Apartment</option>
                <option value="House">House</option>
                <option value="Shutter & Commercial space">Shutter & Commercial space</option>
                <option value="Land">Land</option>
                <option value="Shop">Shop</option>
            </select>
            <label>Remarks</label>
            <select name="cboPropertyRemarks" class="registerInfo" required>
                <option value="">Select remarks</option>
                <option value="For Rent">For Rent</option>
                <option value="For Sell">For Sell</option>
            </select>
			<label>Description</label>
			<textarea name="txtppDescription" class="registerInfo" required></textarea>
            <label>Price (NRP)</label>
			<input class="registerInfo" type="number" name="txtppPrice" placeholder="Amount in NRP" min="0" required>

            <label>Phone number (compulsory)</label>
            <input class="registerInfo" type="text" name="txtHostPhone1" placeholder="10-digit phone number" maxlength="10" pattern="\d{10}" required><br><br>

            <label>Phone number (optional)</label>
            <input class="registerInfo" type="text" name="txtHostPhone2" placeholder="10-digit phone number" maxlength="10" pattern="\d{10}"><br><br>

            <label>Email</label>
            <input class="registerInfo" type="email" name="txtHostEmail" placeholder="Email address" required><br><br>

	        <input class="registerSubmit" type="submit" name="btnList" value="Save">
	    </form>
    </div>

    <script>
        /*Rule select*/
        function toggleRuleSelection() {
            var checkbox = this;
            var label = checkbox.nextElementSibling;
            if (checkbox.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        }

        var checkboxes = document.querySelectorAll('.ruleOption input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', toggleRuleSelection);
        }

        var remarksSelect = document.querySelector('[name="cboPropertyRemarks"]');
        var rentRules = document.getElementById('rentRules');
        var coordinates = document.getElementById('coordinates');
        var mapLink = document.getElementById('mapLink');
        var useLocation = document.getElementById('useLocation');

        function updateListingOptions() {
            var isRent = remarksSelect.value === 'For Rent';
            rentRules.style.display = isRent ? 'block' : 'none';
        }

        function updateMapLink() {
            var query = encodeURIComponent(coordinates.value.trim());
            mapLink.href = query ? 'https://www.google.com/maps/search/?api=1&query=' + query : 'https://www.google.com/maps';
        }

        remarksSelect.addEventListener('change', updateListingOptions);
        coordinates.addEventListener('input', updateMapLink);
        useLocation.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Location is not available in this browser.');
                return;
            }
            navigator.geolocation.getCurrentPosition(function(position) {
                coordinates.value = position.coords.latitude.toFixed(6) + ', ' + position.coords.longitude.toFixed(6);
                updateMapLink();
            }, function() {
                alert('Unable to get your location. Please allow location access.');
            });
        });
        updateListingOptions();
    </script>
</body>
</html>
</body>
</html>