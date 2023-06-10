<?php
    // create short variable names
    $photo_name = (string) $_POST['photo_name'];
    $date_taken = (string) $_POST['date_taken'];
    $photographer = (string) $_POST['photographer'];
    $location = (string) $_POST['location'];
    $uploadImage = (string) $_FILES["file"]["name"];
	$galArray = []; // This array will hold all the variables for displaying the gallery.
?>
<!DOCTYPE html>
<html>
  <head>
   <title>Assignment 1</title>
  </head>
  <body>
  	<tr>
	<p><strong>View All Photos</strong><br>
    </tr>
	
	<form action="gallery.php" method="POST">
    <label for="sort">Sort By:</label>
    <select name="sort" id="sort" onchange="form.submit();">
	<option value="default"></option>
    <option value="name">Name</option>
    <option value="date">Date Taken</option>
    <option value="photographer">Photographer</option>
    <option value="location">Location</option>
    </select>
	<tr>
	</tr>
	
	<tr>
      <form action="index.html" method="post">
      <input type="submit" value="Upload Photo">
    </form>
    </tr>
	
<?php  
  if(isset($_POST['submit1']))
	{
	// Specifies file path for files uploaded onto folder called uploads
	$filepath = "uploads/" . basename($_FILES["file"]["name"]);
		
	// Moves files in temporary folder into uploads
	move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
	}	
	
    $dir    = 'uploads/';
    $files  = scandir($dir);
    $images = array();


    foreach($files as $file) 
    {
        if(fnmatch('*.jpg',$file) || fnmatch('*.JPG',$file) || fnmatch('*.GIF',$file) || fnmatch('*.JPEG',$file) || fnmatch('*.PNG',$file) || fnmatch('*.gif',$file) || fnmatch('*.png',$file) || fnmatch('*.jpeg',$file)) 
        {
            $images[] = $file;
			
        }
    }

    // Make sure to include the path to the image in addition to the user input!
    $outputstring = $uploadImage."\t".$photo_name."\t".$date_taken."\t"
                      .$photographer."\t".$location."\t\n";

	$fp = fopen('/home/titan0/cs431s/cs431s28/homepage/assignment1/data/data.txt', 'ab');
		
    if (!$fp) {
         echo "<p><strong> Unable to write output string into file.
               Please try again later.</strong></p>";
         exit;
    }

       flock($fp, LOCK_EX);
       fwrite($fp, $outputstring, strlen($outputstring));
	   
       flock($fp, LOCK_UN);
       fclose($fp);

       echo "<p>File processed.</p>";
	   
	$fr = fopen('/home/titan0/cs431s/cs431s28/homepage/assignment1/data/data.txt', 'rb');
	
	if (!$fr) {
         echo "<p><strong> Unable to open file for reading.
               Please try again later.</strong></p>";
         exit;
    }
	
	while(!feof($fr)){
        $lines = fgets($fr); // Get the whole line.
        if($lines === false) break; // Remove the empty line if any.
        $subString = explode("\t",$lines); // Separate the lines into variables via explode().
        $tmp = [$subString[0],$subString[1],$subString[2],$subString[3],$subString[4]]; // Push all the substring arrays into the temporary array.
        array_push($galArray,$tmp);
    }
	
	fclose($fr);
	
	// If the user selects the following options from the drop down menu, then sort the
	// photos in accordance to the user choice. Default sort will be by photo name.
	if (isset($_POST["sort"])) {
		if (isset($_POST["sort"]) == "default" || isset($_POST["sort"]) == "name")
		{
			array_multisort(array_column($galArray, 1), SORT_ASC, $galArray);
		}
		else if (isset($_POST["sort"]) == "date")
		{
			array_multisort(array_column($galArray, 2), SORT_ASC, $galArray);
		}
		else if (isset($_POST["sort"]) == "photographer")
		{
			array_multisort(array_column($galArray, 3), SORT_ASC, $galArray);
		}
		else if (isset($_POST["sort"]) == "location")
		{
			array_multisort(array_column($galArray, 4), SORT_ASC, $galArray);
		}
	}

	for ($i = 0; $i < count($galArray); $i++)
	{
		echo '<div class="box boxgallery">';
        echo '<img class="picture-content" src="uploads/'.$galArray[$i][0].'" height=300 width=300/>';
		echo '<div class=data-box">'."Name: ".$galArray[$i][1].'</div>';
		echo '<div class=data-box">'."Date: ".$galArray[$i][2].'</div>';
		echo '<div class=data-box">'."Photographer: ".$galArray[$i][3].'</div>';
		echo '<div class=data-box">'."Location: ".$galArray[$i][4].'</div>';
		echo '</div>';
	}
    ?>
  </body>
</html>