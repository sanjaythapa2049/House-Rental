<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID']) || !isset($_GET['ppID'])) {
        header('Location: userLogin.php');
        exit;
    }

    $uID = (int)$_SESSION['uID'];
    $ppID = (int)$_GET['ppID'];
    $propertyResult = mysqli_query($dbConnect, "SELECT p.* FROM properties p JOIN propertyhosts ph ON ph.PropertyID = p.PropertyID WHERE p.PropertyID = $ppID AND ph.UserID = $uID AND ph.HostType = 'Host'");

    if (mysqli_num_rows($propertyResult) !== 1) {
        http_response_code(403);
        exit('You can only edit your own property.');
    }

    $property = mysqli_fetch_assoc($propertyResult);

    if (isset($_POST['btnSave'])) {
        $propertyName = mysqli_real_escape_string($dbConnect, $_POST['propertyName']);
        $propertyAddress = mysqli_real_escape_string($dbConnect, $_POST['propertyAddress']);
        $propertyCoordinates = mysqli_real_escape_string($dbConnect, $_POST['propertyCoordinates']);
        $propertyRooms = mysqli_real_escape_string($dbConnect, $_POST['propertyRooms']);
        $propertyType = mysqli_real_escape_string($dbConnect, $_POST['propertyType']);
        $propertyRemarks = mysqli_real_escape_string($dbConnect, $_POST['propertyRemarks']);
        $propertyDescription = mysqli_real_escape_string($dbConnect, $_POST['propertyDescription']);
        $propertyPrice = (float)$_POST['propertyPrice'];
        $hostPhone1 = mysqli_real_escape_string($dbConnect, $_POST['hostPhone1']);
        $hostPhone2 = mysqli_real_escape_string($dbConnect, $_POST['hostPhone2']);
        $hostEmail = mysqli_real_escape_string($dbConnect, $_POST['hostEmail']);

        mysqli_query($dbConnect, "UPDATE properties SET PropertyName = '$propertyName', PropertyAddress = '$propertyAddress', PropertyCoordinates = '$propertyCoordinates', PropertyRooms = '$propertyRooms', PropertyType = '$propertyType', PropertyRemarks = '$propertyRemarks', PropertyMaxGuest = 0, PropertyDescription = '$propertyDescription', PropertyPrice = $propertyPrice, HostPhone1 = '$hostPhone1', HostPhone2 = '$hostPhone2', HostEmail = '$hostEmail' WHERE PropertyID = $ppID");
        header("Location: hostListingDetails.php?ppID=$ppID");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Edit Property</title>
</head>
<body id="RegisterBody">
    <div id="registerContainer">
        <h2>Edit Property</h2>
        <form id="Register" method="POST">
            <label>Property name</label>
            <input class="registerInfo" type="text" name="propertyName" value="<?php echo htmlspecialchars($property['PropertyName']); ?>" required>
            <label>Address</label>
            <input class="registerInfo" type="text" name="propertyAddress" value="<?php echo htmlspecialchars($property['PropertyAddress']); ?>" required>
            <label>Map coordinates</label>
            <input class="registerInfo" type="text" name="propertyCoordinates" id="coordinates" value="<?php echo htmlspecialchars($property['PropertyCoordinates']); ?>" required>
            <button type="button" class="registerSubmit" id="useLocation">Use my current location</button>
            <label>Rooms</label>
            <input class="registerInfo" type="text" name="propertyRooms" value="<?php echo htmlspecialchars($property['PropertyRooms']); ?>" required>
            <label>Property type</label>
            <select name="propertyType" class="registerInfo" required>
                <?php foreach (array('Room', 'Flat & Apartment', 'House', 'Shutter & Commercial space', 'Land', 'Shop') as $type) { echo '<option value="' . htmlspecialchars($type) . '"' . ($property['PropertyType'] === $type ? ' selected' : '') . '>' . htmlspecialchars($type) . '</option>'; } ?>
            </select>
            <label>Remarks</label>
            <select name="propertyRemarks" class="registerInfo" required>
                <?php foreach (array('For Rent', 'For Sell') as $remarks) { echo '<option value="' . $remarks . '"' . ($property['PropertyRemarks'] === $remarks ? ' selected' : '') . '>' . $remarks . '</option>'; } ?>
            </select>
            <label>Description</label>
            <textarea name="propertyDescription" class="registerInfo" required><?php echo htmlspecialchars($property['PropertyDescription']); ?></textarea>
            <label>Price</label>
            <input class="registerInfo" type="number" name="propertyPrice" min="0" step="0.01" value="<?php echo htmlspecialchars($property['PropertyPrice']); ?>" required>
            <label>Phone number (compulsory)</label>
            <input class="registerInfo" type="text" name="hostPhone1" maxlength="10" pattern="\d{10}" value="<?php echo htmlspecialchars($property['HostPhone1'] ?? ''); ?>" required>
            <label>Phone number (optional)</label>
            <input class="registerInfo" type="text" name="hostPhone2" maxlength="10" pattern="\d{10}" value="<?php echo htmlspecialchars($property['HostPhone2'] ?? ''); ?>">
            <label>Email</label>
            <input class="registerInfo" type="email" name="hostEmail" value="<?php echo htmlspecialchars($property['HostEmail'] ?? ''); ?>" required>
            <button class="registerSubmit" type="submit" name="btnSave">Save changes</button>
        </form>
    </div>
    <script>
        document.getElementById('useLocation').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Location is not available in this browser.');
                return;
            }
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('coordinates').value = position.coords.latitude.toFixed(6) + ', ' + position.coords.longitude.toFixed(6);
            }, function() {
                alert('Unable to get your location. Please allow location access.');
            });
        });
    </script>
</body>
</html>