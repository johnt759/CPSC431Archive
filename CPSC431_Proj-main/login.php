<?php
if (isset($_POST["submit"])) {
	// Set up the variables to verify login.
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	// Now verify whether the login was processed successfully.
	if ($username == 'admin' && $password == 'admin')
	{
		session_start();
		$_SESSION['loggedin'] = TRUE;
		header("location: manage_user.php");
	}
	else if ($username == 'nutritionist' && $password == 'nutritionist')
	{
		session_start();
		$_SESSION['loggedin'] = TRUE;
		header("location: meals.php");
	}
	else if ($username == 'advisor' && $password == 'advisor')
	{
		session_start();
		$_SESSION['loggedin'] = TRUE;
		header("location: weekly_average.php");
	}
	
	// If none of the if statements above are true, assume it is the
	// standard user logging in. In that case, check the database
	// whether said user exists in the table.
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
	
	// Select the username from the users database table and verify if both the name and password matches.
	$login_query = "SELECT * FROM Users WHERE Username=? AND Password=?";
	
	$login_result = $db_connect->prepare($login_query);
	$login_result->bind_param('ss', $username, $password);
	$login_result->execute();
	$login_result->store_result();
	
	if ($login_result->num_rows>0)
	{
		session_start();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['user'] = $username;
		header("location: profile.php");
	}
	else
	{
		echo "Invalid username or password. Please go back and try again.";
		exit;
	}
	$login_result->close();
	$db_connect->close();
}
?>
