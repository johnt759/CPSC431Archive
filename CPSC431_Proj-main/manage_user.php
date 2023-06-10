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
   <title>Manage User</title>
  </head>
  <body>
    <form action="delete_user_result.php" method="post" enctype="multipart/form-data">
    <table style="border: 0px;">
	<tr>
		<h1> Welcome admin! </h1>
	</tr>
	<tr>
	</tr>
	<tr>
      <h2>Please type in the username to delete from the database list below.<h2>
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
	
    echo '<div class=data-box"><strong>List of current users</strong></div>';
	
	while($resultStatement->fetch()) {
        echo '<div class=data-box">'.$username.'</div>';
    }
	
	$resultStatement->free_result();
	$db_connect->close();
    ?>
    <tr>
      <td>Username:</td>
      <td><input type="text" name="username" size="15" maxlength="50" /></td>
    </tr>
    <tr>
		<h3> Warning: All entries associated with the user will be deleted and cannot be undone! </h3>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"><input type="submit" value="Delete User" name="confirm_delete"/></td>
    </tr>
  <tr>
    <a href="logout.php">Logout</a>
   </tr>
  </body>
</html>