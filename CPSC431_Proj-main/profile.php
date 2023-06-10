<?php
	session_start();
	if(!isset($_SESSION['loggedin']))
	{
		echo "<p>You cannot go that way. Login in order to see that page.</p>";
		exit;
	}
?>

<!DOCTYPE html>
<html>
  <head>
   <title>My Weight Tracker</title>
  </head>
  <body>
   <tr>
	<h1>My Weight Tracker</h1>
   </tr>
  <?php
	// Connect to the database and obtain the user data from it.
	// Then, use that to display the information in the profile page.
	$host ="mariadb";
    $uname = "cs431s28";
    $pwd = 'cee9FaTh';
    $db_name = "cs431s28";

    $file_path = 'profiles/';

	$db_connect = new mysqli($host, $uname, $pwd, $db_name) or die("Could not connect to database." .mysqli_error());
	
	$searchQuery = "SELECT Username, Gender, Start_Weight, Goal, Image_Profile FROM Users WHERE Username = ?";
	$newStatement = $db_connect ->prepare($searchQuery);
	$newStatement->bind_param('s', $_SESSION['user']);
	$newStatement->execute();
	
	// Now bind all of the results into the variables.
	$newStatement->store_result();
	$newStatement->bind_result($username, $gender, $start_weight, $goal, $profileImage);
	
    while($newStatement->fetch()) {
        echo '<div class=data-box">'."Name: ".$username.'</div>';
        echo '<div class=data-box">'."Gender: ".$gender.'</div>';
        echo '<div class=data-box">'."Starting Weight: ".$start_weight.'</div>';
        echo '<div class=data-box">'."Goal: ".$goal.'</div>';
		echo "<p></p>";
        echo '<img class="picture-content" src="profiles/'.$profileImage.'" height=300 width=300/>';
    }
	$newStatement->free_result();
	$db_connect->close();
  ?>
   <tr>
    <a href="update_profile.html">Update Profile</a>
   </tr>
   <tr>
    <a href="logout.php">Logout</a>
   </tr>
  </body>
</html>
