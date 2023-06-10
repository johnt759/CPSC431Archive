<?php
	session_start();
	if(!isset($_SESSION['loggedin']))
	{
		echo "<p>You cannot go that way. Login in order to see that page.</p>";
		exit;
	}
	if(isset($_POST['submit_weight']))
	{
	// create short variable names
    $username = $_POST['username'];
    $current_weight = $_POST['current_weight'];
	
	$_SESSION['username'] = $username;
	$current_weight = (double)$current_weight; // Convert current weight to double.
	
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
	
	$searchQuery2 = "SELECT Start_Weight, Goal FROM Users WHERE Username = ?";
	$newStatement = $db_connect ->prepare($searchQuery2);
	$newStatement->bind_param('s', $_SESSION['username']);
	$newStatement->execute();
	$newStatement->store_result();
	$newStatement->bind_result($new_start_weight, $new_goal);
	while($newStatement->fetch()) {
        $start_weight = $new_start_weight;
		$goal = $new_goal;
    }
	$newStatement->free_result();
	$newStatement->close();
	
	// Convert the starting weight into a double.
	$start_weight = (double)$start_weight;
	
	// Calculate the user's weekly average by obtaining the difference between the current weight and the starting weight.
	// The user is considered to meet his or her goal if one of the following conditions are true:
	// Maintain Weight: The difference must be between -3.0 and 3.0 inclusive.
	// Lose Weight: The difference must be less than -3.0.
	// Gain Weight: The difference must be more than 3.0.
	// If none of the conditions are true, indicate that the user isn't reaching the goal.
	$weekly_average = $current_weight - $start_weight;
	if ($goal == "Maintain Weight")
	{
		if (($weekly_average <= 3.0) and ($weekly_average >= -3.0))
		{
			$feedback = "You're doing a good job, you are getting closer to your goal!";
		}
		else
		{
			$feedback = "You need improvement, it looks like you are not working hard enough towards your goal!";
		}
	}
	if ($goal == "Lose Weight")
	{
		if ($weekly_average < -3.0)
		{
			$feedback = "You're doing a good job, you are getting closer to your goal!";
		}
		else
		{
			$feedback = "You need improvement, it looks like you are not working hard enough towards your goal!";
		}
	}
	if ($goal == "Gain Weight")
	{
		if ($weekly_average > 3.0)
		{
			$feedback = "You're doing a good job, you are getting closer to your goal!";
		}
		else
		{
			$feedback = "You need improvement, it looks like you are not working hard enough towards your goal!";
		}
	}
	
	// Now convert the starting weight, current weight, and weekly average to strings.
	$start_weight = (string)$start_weight;
	$current_weight = (string)$current_weight;
	$weekly_average = (string)$weekly_average;
	
	$_SESSION['average'] = $weekly_average;
	
	// Insert the results into the database table.
	$insertQuery = "INSERT INTO Average_Weight VALUES (?, ?, ?, ?, ?)";
	$newStatement = $db_connect->prepare($insertQuery);
	$newStatement->bind_param("sssss", $username, $start_weight, $current_weight, $weekly_average, $feedback);
	$newStatement->execute();
	
	if ($newStatement->affected_rows <= 0)
	{
		echo "<p>Unable to add weekly average into the database.</p>";
		exit;
	}

	$newStatement->close();
	$db_connect->close(); // Close the database after the operation.
	}
?>

<!DOCTYPE html>
<html>
  <head>
   <title>Weekly Average</title>
  </head>
  <body>
  <tr>
		<h1> Weekly Average Results </h1>
  </tr>
  <tr>
		<h2> Here is the summary for the user's weekly average weight. </h2>
  </tr>
  <?php
	// Now connect to the database and display the weekly average summary to the user.
    $host ="mariadb";
    $uname = "cs431s28";
    $pwd = 'cee9FaTh';
    $db_name = "cs431s28";
		
	$db_connect = new mysqli($host, $uname, $pwd, $db_name) or die("Could not connect to database." .mysqli_error());
	
	// Retrieve the weekly average weight summary for the user.
	$searchQuery3 = "SELECT * FROM Average_Weight WHERE Username = ? AND Weekly_Average = ?";
	
	$newStatement2 = $db_connect ->prepare($searchQuery3);
	$newStatement2->bind_param('ss', $_SESSION['username'], $_SESSION['average']);
	$newStatement2->execute();
	
	// Now bind all of the results into the variables.
	$newStatement2->store_result();
	$newStatement2->bind_result($username, $start_weight, $current_weight, $weekly_average, $feedback);
	
    while($newStatement2->fetch()) {
        echo '<div class=data-box">'."Name: ".$username.'</div>';
        echo '<div class=data-box">'."Starting Weight: ".$start_weight.'</div>';
        echo '<div class=data-box">'."Current Weight: ".$current_weight.'</div>';
        echo '<div class=data-box">'."Weekly Average: ".$weekly_average.'</div>';
        echo '<div class=data-box">'."Feedback: ".$feedback.'</div>';
		echo "<p></p>";
    }
	$newStatement2->free_result();
	$db_connect->close();
  ?>
  <tr>
    <a href="weekly_average.php">Return</a>
   </tr>
  <tr>
    <a href="logout.php">Logout</a>
   </tr>
  </body>
</html>
