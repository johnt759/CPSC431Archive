<?php
	if(isset($_POST['submit_user']))
	{
	// create short variable names
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $start_weight = $_POST['start_weight'];
    $goal = $_POST['goal'];
    $profileName = $_FILES["file"]["name"];
	$profileType = $_FILES["file"]["type"]; // Needed to verify the file type.
	
	// Specifies file path for files uploaded onto folder called uploads
	$profileImage = "profiles/" . basename($_FILES["file"]["name"]);
		
	// Moves files in temporary folder into uploads
	move_uploaded_file($_FILES["file"]["tmp_name"], $profileImage);
	
	if ($gender == "Male")
	{
		$new_gender = "Male";
	}
	else if ($gender == "Female")
	{
		$new_gender = "Female";
	}
	else if ($gender == "Neutral")
	{
		$new_gender = "Neutral";
	}
	
	if ($goal == "maintain")
	{
		$new_goal = "Maintain Weight";
	}
	else if ($goal == "lose_weight")
	{
		$new_goal = "Lose Weight";
	}
	else if ($goal == "gain_weight")
	{
		$new_goal = "Gain Weight";
	}

	// Now verify if the entered password is a valid one.
	if (strlen($password) < 8)
	{
		echo "<p>The password must be at least 8 characters long.</p>";
		exit;
	}
	if ((!preg_match('#[A-Za-z]+#', $password)) || (!preg_match('#[0-9]+#', $password)))
	{
		echo "<p>The password is not valid. Go back and try again.</p>";
		exit;
	}

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

	// If a new username is added, check to see if the user already exists.
	$searchQuery = "SELECT * FROM Users WHERE Username = ?";
	$newStatement = $db_connect ->prepare($searchQuery);
	$newStatement->bind_param('s', $username);
	$newStatement->execute();

	if ($newStatement->affected_rows > 0)
	{
		echo "<p>The username already exists. Please try a different one.</p>";
		exit;
	}
	
	$newStatement->close();
	
	// Assuming there are no errors, insert the new user into the database.
	$insertQuery = "INSERT INTO Users VALUES (?,?,?,?,?,?)";
	$newStatement = $db_connect ->prepare($insertQuery);
	$newStatement->bind_param('ssssss', $username, $password, $new_gender, $start_weight, $new_goal, $profileName);
	$newStatement->execute();
	
	// Now verify if the query is processed successfully or not.
	if ($newStatement->affected_rows <= 0)
	{
		echo "<p>Unable to add user into the database.</p>";
		exit;
	}
	
	$newStatement->close();
	$db_connect->close(); // Close the database after the operation.
	}
?>

<!DOCTYPE html>
<html>
  <head>
   <title>Add User</title>
  </head>
  <body>
  <tr>
		<h1> Success! </h1>
  </tr>
  <tr>
		<h2> The user is now added into the database. </h2>
  </tr>
  <tr>
    <a href="index.html">Return to Login</a>
   </tr>
  </body>
</html>
