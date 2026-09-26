<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='adminLogin.php'</script>";
    }

    $aID = $_SESSION['aID'];

    if (isset($_POST['btnAdd'])) 
	{
		$ciNameIn = $_POST['txtciName'];

		/*Check existing cities*/
		$checkCity = "SELECT * FROM cities WHERE CityName = '$ciNameIn'";
		$result = mysqli_query($dbConnect, $checkCity);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			echo "<script>window.alert('This city already exists in the list. Please try another one.')</script>";
			echo "<script>window.location='adminCity.php'</script>";
		}

		else
		{
			$insert = "INSERT INTO cities(CityName)
					   VALUES('$ciNameIn')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('New city added.')</script>";
                echo "<script>window.location='adminCity.php'</script>";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>Cities</title>
</head>
<body>
    <div class="homeSection">
        <a href="adminMain.php" class="homeBtn"><i class="fas fa-home"></i> Home</a>
    </div>
    <div class="container">
        <h2>Cities</h2>

        <div class="addButton">
            <a href="#" class="btn btnAdd">Add city</a>
        </div>

        <div class="deleteButton">
            <a href="#" class="btn btnDelete">Delete</a>
        </div>

<table class="cityTable">
            <tr>
                <th>City ID</th>
                <th>Name</th>
            </tr>

            <?php 
            $citySelect = "SELECT * FROM cities";
            $result = mysqli_query($dbConnect, $citySelect);

            $serial = 0;
            while ($array = mysqli_fetch_array($result)) 
            { 
                $serial++;
                $ciID = $array['CityID'];
                $ciName = $array['CityName'];

                echo "<tr data-ciid='$ciID'>";
                echo "<td>$serial</td>";
                echo "<td>$ciName</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div id="addCityPopup">
            <div class="popupContent">
                <span class="closePopup" id="cancelAddCity">&times;</span>
                <h3>Add New City</h3>

                <form id="Register" action="adminCity.php" method="POST">
                    <label>City name</label>
                    <input class="registerInfo" type="text" name="txtciName" required><br><br>

<input class="registerSubmit" type="submit" name="btnAdd" value="Save">
                </form>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
        var cityTable = document.querySelector('.cityTable');
        var deleteButton = document.querySelector('.deleteButton');
        var deleteLink = document.querySelector('.btnDelete');
        var addButton = document.querySelector('.btnAdd');
        var addCityPopup = document.getElementById('addCityPopup');
        var cancelAddCityButton = document.getElementById('cancelAddCity');
        
        var selectedRow = null;

        function handleRowClick(event) {
            var clickedRow = event.target.closest('tr');
            
            if (clickedRow && clickedRow.dataset.ciid) {
                if (selectedRow === clickedRow) {
                    clickedRow.classList.remove('selected');
                    deleteButton.classList.remove('visible');
                    selectedRow = null;
                } else {
                    if (selectedRow) {
                        selectedRow.classList.remove('selected');
                    }
                    
                    clickedRow.classList.add('selected');
                    deleteButton.classList.add('visible');
                    selectedRow = clickedRow;
                    
                    var cityID = clickedRow.dataset.ciid;
                    deleteLink.href = 'adminCityDelete.php?ciID=' + cityID;
                }
            }
        }

        function handleDocumentClick(event) {
            if (!cityTable.contains(event.target) && !deleteButton.contains(event.target)) {
                if (selectedRow) {
                    selectedRow.classList.remove('selected');
                    selectedRow = null;
                }
                deleteButton.classList.remove('visible');
            }
        }

        function showAddCityPopup() {
            addCityPopup.style.display = 'block';
        }

        function hideAddCityPopup() {
            addCityPopup.style.display = 'none';
        }

        cityTable.addEventListener('click', handleRowClick);
        document.addEventListener('click', handleDocumentClick);
        addButton.addEventListener('click', showAddCityPopup);
        cancelAddCityButton.addEventListener('click', hideAddCityPopup);

        window.onclick = function(event) {
            if (event.target == addCityPopup) {
                hideAddCityPopup();
            }
        }
    };
    </script>
</body>
</html>