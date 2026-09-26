<?php 
	include('dbConnect.php');

	$formValues = [
		'email' => '',
		'name' => '',
		'password' => '',
		'phone' => ''
	];

	if (isset($_POST['btnRegister'])) 
	{
		$uEmail = trim($_POST['txtuEmail'] ?? '');
		$uName = trim($_POST['txtuName'] ?? '');
		$uPassword = trim($_POST['txtuPassword'] ?? '');
		$uPh = trim($_POST['txtuPh'] ?? '');

		$formValues['email'] = $uEmail;
		$formValues['name'] = $uName;
		$formValues['password'] = $uPassword;
		$formValues['phone'] = $uPh;

		$invalidField = '';
		$errorMessage = '';

		/*Profile img upload*/
		$uPp = $_FILES['txtuPp']['name'] ?? '';
		if ($uPp !== '') {
			$folder = "profileImg/";
			$uPpFileName = $folder."_".$uPp;
			$copy = copy($_FILES['txtuPp']['tmp_name'], $uPpFileName);
			if (!$copy) 
			{
				echo "<p>An error occured. Cannot upload picture.</p>";
			}
		}

		/*Check password Format*/
		$passwordNumber = preg_match('@[0-9]@', $uPassword);
		$passwordUpperCase = preg_match('@[A-Z]@', $uPassword);
		$passwordLowerCase = preg_match('@[a-z]@', $uPassword);
		$passwordSpecial = preg_match('@[^\w]@', $uPassword);

		$phoneValid = preg_match('/^[0-9]{10}$/', $uPh);

		/*Check existing emails*/
		$checkuEmail = "SELECT * FROM users WHERE UserEmail = '$uEmail'";
		$result = mysqli_query($dbConnect, $checkuEmail);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			$invalidField = 'email';
			$formValues['email'] = '';
			$errorMessage = 'The entered email already exists. Please try another one.';
		}
		else if (strlen($uPassword)<8 || !$passwordNumber || !$passwordUpperCase || !$passwordLowerCase || !$passwordSpecial)
		{
			$invalidField = 'password';
			$formValues['password'] = '';
			$errorMessage = 'Password must be at least 8 characters in length and must contain at least one uppercase, one lowercase and, one number, and one special character.';
		}
		else if (!$phoneValid)
		{
			$invalidField = 'phone';
			$formValues['phone'] = '';
			$errorMessage = 'Phone number must be exactly 10 digits.';
		}
		else
		{
			$insert = "INSERT INTO users(UserEmail, UserName, UserPp, UserPassword, UserPh, UserBalance, GuestVerificationStatus, AccountStatus)
							VALUES('$uEmail','$uName', '$uPpFileName', '$uPassword', '$uPh', '0', 'Verified', 'Active')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('User register successful.')</script>";
				echo "<script>window.location='userLogin.php'</script>";
				return;
			}
		}

		if ($errorMessage !== '') {
			echo "<script>window.alert('" . addslashes($errorMessage) . "')</script>";
		}
	}
 ?>

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="roomaStyle.css">
	<title></title>
</head>

<body id="RegisterBody">
	<div id="registerContainer">
		<div id="logoContainer">
	    	<img id="registerLogo" src="websiteImg/logo2.png"><br>
	    </div>

	    <form id="Register" action="userRegister.php" method="POST" enctype="multipart/form-data">
			<label>Email</label>
			<input class="registerInfo" type="email" name="txtuEmail" value="<?php echo htmlspecialchars($formValues['email']); ?>" placeholder="Email" required><br>

			<label>Name</label>
			<input class="registerInfo" type="text" name="txtuName" value="<?php echo htmlspecialchars($formValues['name']); ?>" placeholder="Name" required><br>

            <label>Profile picture</label>
		 	<input class="registerInfo" type="file" name="txtuPp" required/><br><br>

			<label>Password</label>
			<input class="registerInfo" type="password" name="txtuPassword" value="<?php echo htmlspecialchars($formValues['password']); ?>" placeholder="Password" required><br>

			<label>Phone number</label>
			<input class="registerInfo" type="text" name="txtuPh" value="<?php echo htmlspecialchars($formValues['phone']); ?>" placeholder="Phone number" required><br><br>

	        <input class="registerSubmit" type="submit" name="btnRegister" value="Register">
	    </form>
		<p>Already registered? <a href="userLogin.php">Log in!</a></p>
    </div>
</body>
</html>