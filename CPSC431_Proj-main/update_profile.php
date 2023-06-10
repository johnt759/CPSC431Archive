<?php
	session_start();
	if(!isset($_SESSION['loggedin']))
	{
		echo "<p>You cannot go that way. Login in order to see that page.</p>";
		exit;
	}
	
	if(isset($_POST['update']))
	{
	// create short variable names
    $newProfileName = $_FILES["file"]["name"];
	$newProfileType = $_FILES["file"]["type"]; // Needed to verify the file type.
	
	// Specifies file path for files uploaded onto folder called uploads
	$newProfileImage = "profiles/" . basename($_FILES["file"]["name"]);
		
	// Moves files in temporary folder into uploads
	move_uploaded_file($_FILES["file"]["tmp_name"], $newProfileImage);
	
	// Connect to the database if possible.
	$host = "mariadb";
	$user = "cs431s28";
	$pass = "cee9FaTh";
	$data = "cs431s28";
	@$db_connect = new mysqli($host, $user, $pass, $data);
	
	if (mysqli_connect_errno())
	{
		echo "<p>Can't connect to database.</p>";
		exit;
	}

	// Now replace the current profile image with the new uploaded one.
	$updateQuery = "UPDATE Users SET Image_Profile=? WHERE Username=?";
	$newStatement = $db_connect ->prepare($updateQuery);
	$newStatement->bind_param('ss', $newProfileName, $_SESSION['user']);
	$newStatement->execute();
	
	// Now verify if the query is processed successfully or not.
	if ($newStatement->affected_rows <= 0)
	{
		echo "<p>Unable to update profile image.</p>";
		exit;
	}
	$newStatement->close();
	$db_connect->close(); // Close the database after the operation.
	}
?>

<!DOCTYPE html>
<html>
  <head>
   <title>Update Profile</title>
  </head>
  <body>
  <tr>
		<h1> Profile updated. </h1>
  </tr>
  <tr>
		<h2> Your new profile image is now ready for display! </h2>
  </tr>
  <tr>
    <a href="profile.php">Return</a>
   </tr>
  </body>
</html>
