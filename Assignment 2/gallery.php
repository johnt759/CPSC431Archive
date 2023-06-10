  
<?php
	if(isset($_POST['submit1']))
	{
	// create short variable names
    $photo_name = $_POST['photo_name'];
    $date_taken = $_POST['date_taken'];
    $photographer = $_POST['photographer'];
    $location = $_POST['location'];
    $uploadImage = $_FILES["file"]["name"];
	$fileType = $_FILES["file"]["type"]; // Needed to get the type of file.
		
	// Specifies file path for files uploaded onto folder called uploads
	$filepath = "uploads/" . basename($_FILES["file"]["name"]);
		
	// Moves files in temporary folder into uploads
	move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
		
	
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
	
	// Insert the image and metadata into the database.
	$insertQuery = "INSERT INTO Images VALUES (?,?,?,?,?)";
	$newStatement = $db_connect ->prepare($insertQuery);
	$newStatement->bind_param('sssss', $uploadImage, $photo_name, $date_taken, $photographer, $location);
	$newStatement->execute();
	
	// Now verify if the query is processed successfully or not.
	if ($newStatement->affected_rows <= 0)
	{
		echo "<p>Unable to upload image into database.</p>";
		exit;
	}
	
	$db_connect->close(); // Close the database after the operation.
	}
?>
<!DOCTYPE html>
<html>
  <head>
   <title>Assignment 2</title>
  </head>
  <body>
  	<tr>
	<h1>View All Photos</h1>
    </tr>
	
	<form action="gallery.php" method="post" enctype="multipart/form-data">
    <h2>Sort By:
    <select name="sort" id="sort-choice">
	<option value="default"></option>
    <option value="name">Name</option>
    <option value="date">Date Taken</option>
    <option value="photographer">Photographer</option>
    <option value="location">Location</option>
    </select>
	<button type="submit" name="confirm">Confirm</button>
	</h2>
	</form>
	<tr>
	</tr>
	
	<tr>
	  <form action="gallery.php" method="post" enctype="multipart/form-data">
      <button type="submit" formaction="index.html">Upload Photo</button>
    </form>
    </tr>

	<?php
        $host ="mariadb";
        $uname = "cs431s28";
        $pwd = 'cee9FaTh';
        $db_name = "cs431s28";

        $file_path = 'uploads/';

	$db_connect = new mysqli($host, $uname, $pwd, $db_name) or die("Could not connect to database." .mysqli_error());
	
	$choice = "name";
	if (isset($_POST["confirm"])) {
		$choice = $_POST["sort"];
	}
	echo "<p>Sorting by ".$choice."</p>";
	// This will be the default sorting choice if no other sorting options are applicable below.
	$select_query = "SELECT image_file, image_name, date_taken, photographer, location FROM Images ORDER BY image_name";
	if ($choice == "name")
	{
		$select_query = "SELECT image_file, image_name, date_taken, photographer, location FROM Images ORDER BY image_name";
	}
	else if ($choice == "date")
	{
		$select_query = "SELECT image_file, image_name, date_taken, photographer, location FROM Images ORDER BY date_taken";
	}
	else if ($choice == "photographer")
	{
		$select_query = "SELECT image_file, image_name, date_taken, photographer, location FROM Images ORDER BY photographer";
	}
	else if ($choice == "location")
	{
		$select_query = "SELECT image_file, image_name, date_taken, photographer, location FROM Images ORDER BY location";
	}
	
    $statement = $db_connect->prepare($select_query);
    $statement->execute();
    $statement->store_result();

    $statement->bind_result($uploadImage, $photo_name, $date_taken, $photographer, $location);

	// Display the images and metadata while there's still some to retrieve from database.
    while($statement->fetch()) {
        echo '<img class="picture-content" src="uploads/'.$uploadImage.'" height=300 width=300/>';
        echo '<div class=data-box">'."Name: ".$photo_name.'</div>';
        echo '<div class=data-box">'."Date: ".$date_taken.'</div>';
        echo '<div class=data-box">'."Photographer: ".$photographer.'</div>';
        echo '<div class=data-box">'."Location: ".$location.'</div>';
    }

	$statement->free_result();
	$db_connect->close();
	?>
</body>
</html>