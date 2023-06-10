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
   <title>Meal Tracker</title>
  </head>
  <body>
    <form action="meals_add.php" method="post" enctype="multipart/form-data">
    <table style="border: 0px;">
	<tr>
		<h1> Meal Tracker </h1>
	</tr>
	<?php
    $host ="mariadb";
    $uname = "cs431s28";
    $pwd = 'cee9FaTh';
    $db_name = "cs431s28";
		
	@$db_connect = new mysqli($host, $uname, $pwd, $db_name);
	
	if (mysqli_connect_errno())
	{
		echo "<p>Can't connect to database.</p>";
		exit;
	}
	
	// Order the meal history for the user by date in ascending order.
	$displayQuery = "SELECT Username FROM Users";
	
	$resultStatement = $db_connect->prepare($displayQuery);
	$resultStatement->execute();
	$resultStatement->store_result();
	$resultStatement->bind_result($username);
	
    echo '<div class=data-box"><strong>List of users</strong></div>';
	
	while($resultStatement->fetch()) {
        echo '<div class=data-box">'.$username.'</div>';
    }
	
	$resultStatement->free_result();
	$db_connect->close();
  ?>
	<tr>
      <h2>Please enter the user's meal history.<h2>
    </tr>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="username" size="15" maxlength="50" /></td>
    </tr>
    <tr>
      <td>Date:</td>
      <td><input type="date" name="date" size="15" maxlength="50" /></td>
    </tr>
    <tr>
      <td>Breakfast:</td>
      <td><input type="text" name="breakfast" size="15" maxlength="50" /></td>
    </tr>
    <tr>
      <td>Lunch:</td>
      <td><input type="text" name="lunch" size="15" maxlength="50"</td>
    </tr>
    <tr>
      <td>Dinner:</td>
      <td><input type="text" name="dinner" size="15" maxlength="50" /></td>
    </tr>
    <tr>
      <td>Daily Weight:</td>
      <td><input type="text" name="daily_weight" size="15" maxlength="50" /></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"><input type="submit" value="Add Meal" name="submit_meal"/></td>
    </tr>
  <tr>
    <a href="logout.php">Logout</a>
   </tr>
  </body>
</html>
