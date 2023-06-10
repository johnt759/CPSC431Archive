<?php
	session_start();
	if(!isset($_SESSION['loggedin']))
	{
		echo "<p>You cannot go that way. Login in order to see that page.</p>";
		exit;
	}
	
	if(isset($_POST['confirm_delete']))
	{
    $username = $_POST['username'];

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
	
	// Delete all of the table entries associated with the username that's selected for deletion.
	$deleteQuery1 = "DELETE FROM Users WHERE Username = ?";
	$deleteQuery2 = "DELETE FROM Meals WHERE Username = ?";
	$deleteQuery3 = "DELETE FROM Average_Weight WHERE Username = ?";
	
	$deleteStatement = $db_connect->prepare($deleteQuery1);
	$deleteStatement->bind_param('s', $username); 
	$deleteStatement->execute();
	
	if ($deleteStatement === FALSE)
	{
		echo "<p>Can't delete from the database.</p>";
		exit;
	}
	
	$deleteStatement->close();
	
	$deleteStatement2 = $db_connect->prepare($deleteQuery2);
	$deleteStatement2->bind_param('s', $username); 
	$deleteStatement2->execute();
	
	if ($deleteStatement2 === FALSE)
	{
		echo "<p>Can't delete from the database.</p>";
		exit;
	}
	
	$deleteStatement2->close();
	
	$deleteStatement3 = $db_connect->prepare($deleteQuery3);
	$deleteStatement3->bind_param('s', $username); 
	$deleteStatement3->execute();
	
	if ($deleteStatement3 === FALSE)
	{
		echo "<p>Can't delete from the database.</p>";
		exit;
	}
	
	$deleteStatement3->close();
	
	$db_connect->close();
	}
?>

<!DOCTYPE html>
<html>
  <head>
   <title>Delete User</title>
  </head>
  <body>
  <tr>
		<h1> User Deleted Successfully. </h1>
  </tr>
  <tr>
    <a href="manage_user.php">Return</a>
   </tr>
  </body>
</html>
