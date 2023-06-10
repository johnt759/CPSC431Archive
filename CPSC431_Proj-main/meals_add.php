<?php
	session_start();
	if(!isset($_SESSION['loggedin']))
	{
		echo "<p>You cannot go that way. Login in order to see that page.</p>";
		exit;
	}
	if(isset($_POST['submit_meal']))
	{
	// create short variable names
    $username = $_POST['username'];
    $date = $_POST['date'];
    $breakfast = $_POST['breakfast'];
    $lunch = $_POST['lunch'];
    $dinner = $_POST['dinner'];
    $daily_weight = $_POST['daily_weight'];
	
	$_SESSION['username'] = $username; // Save this username when displaying the meal history for the user.
	
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
	
	// First, check to see if the username exists.
	$searchQuery = "SELECT * FROM Users WHERE Username = ?";
	$newStatement = $db_connect ->prepare($searchQuery);
	$newStatement->bind_param('s', $_SESSION['username']);
	$newStatement->execute();
	$newStatement->store_result();
	if ($newStatement->num_rows <= 0)
	{
		echo "<p>The username does not exist. Please go back and re-enter.</p>";
		exit;
	}
	$newStatement->close();
	
	// Before inserting the meal entry into the database, ensure that
	// the date must be unique for each user's meal history. However, it's
	// permissible to allow multiple users to share the same date as long as
	// the username and date remain distinct from each other.
	// In other words, there can be at most one meal history of breakfast,
	// lunch, and dinner for each date for each user.
	$checkQuery = "SELECT * FROM Meals WHERE Date = ? AND Username = ?";
	$newStatement = $db_connect->prepare($checkQuery);
	$newStatement->bind_param('ss', $date, $_SESSION['username']);
	$newStatement->execute();
	$newStatement->store_result();
	if ($newStatement->num_rows > 0)
	{
		echo "<p>Sorry, you cannot add more than one meal history on the same date.</p>";
		exit;
	}
	$newStatement->close();
	
	// Insert the meal entry into the database.
	$insertQuery = "INSERT INTO Meals VALUES (?,?,?,?,?,?)";
	$newStatement = $db_connect ->prepare($insertQuery);
	$newStatement->bind_param('ssssss', $date, $_SESSION['username'], $breakfast, $lunch, $dinner, $daily_weight);
	$newStatement->execute();
	
	// Now verify if the query is processed successfully or not.
	if ($newStatement->affected_rows <= 0)
	{
		echo "<p>Cannot add meal history into database.</p>";
		exit;
	}
	
	$newStatement->close();
	$db_connect->close(); // Close the database after the operation.
	}
?>

<!DOCTYPE html>
<html>
  <head>
   <title>Meal Tracker</title>
  </head>
  <body>
  <tr>
		<h1> Meal History </h1>
  </tr>
  <tr>
		<h2> Here is the meal history list for all users. </h2>
  </tr>
  <?php
	// Now connect to the database and display the meal history to the user.
    $host ="mariadb";
    $uname = "cs431s28";
    $pwd = 'cee9FaTh';
    $db_name = "cs431s28";
		
	$db_connect = new mysqli($host, $uname, $pwd, $db_name) or die("Could not connect to database." .mysqli_error());
	
	// Order the meal history for all users by date in ascending order.
	$displayQuery = "SELECT * FROM Meals ORDER BY Date";
	
	$resultStatement = $db_connect->prepare($displayQuery);
	$resultStatement->execute();
	$resultStatement->store_result();
	$resultStatement->bind_result($date, $username, $breakfast, $lunch, $dinner, $daily_weight);
	
	while($resultStatement->fetch()) {
        echo '<div class=data-box">'."Date: ".$date.'</div>';
        echo '<div class=data-box">'."Name: ".$username.'</div>';
        echo '<div class=data-box">'."Breakfast: ".$breakfast.'</div>';
        echo '<div class=data-box">'."Lunch: ".$lunch.'</div>';
        echo '<div class=data-box">'."Dinner: ".$dinner.'</div>';
        echo '<div class=data-box">'."Daily Weight: ".$daily_weight.'</div>';
		echo "<p></p>";
    }
	
	$resultStatement->free_result();
	$db_connect->close();
  ?>
  <tr>
    <a href="meals.php">Add another meal</a>
   </tr>
  <tr>
    <a href="logout.php">Logout</a>
   </tr>
  </body>
</html>
