<?php 
if( $_REQUEST['did_upload'] == 1 ):
	//file uploading stuff begins
	
	$target_path = "uploads/";
	
	//list of image sizes to generate. make sure a column name in your DB matches up with a key for each size
	$sizes = array(
		'thumb_img' => 150,
		'medium_img' => 300 
	);	
	
	
		
	// This is the temporary file created by PHP
	$uploadedfile = $_FILES['uploadedfile']['tmp_name'];
	// Capture the original size of the uploaded image
	list($width,$height) = getimagesize($uploadedfile);
	
	//make sure the width and height exist, otherwise, this is not a valid image
	if($width > 0 AND $height > 0){
	
	//what kind of image is it
	$filetype = $_FILES['uploadedfile']['type'];
	
	switch($filetype){
		case 'image/gif':
			// Create an Image from it so we can do the resize
			$src = imagecreatefromgif($uploadedfile);
		break;
		
		case 'image/pjpeg':
		case 'image/jpg':
		case 'image/jpeg': 
			// Create an Image from it so we can do the resize
			$src = imagecreatefromjpeg($uploadedfile);
		break;
	
		case 'image/png':
			// Create an Image from it so we can do the resize
			$required_memory = Round($width * $height * $size['bits']);
			$new_limit=memory_get_usage() + $required_memory;
			ini_set("memory_limit", $new_limit);
			$src = imagecreatefrompng($uploadedfile);
			ini_restore ("memory_limit");
		break;
		
			
	}
	//for filename
	$randomsha = sha1(microtime());
	
	//do it!  resize images
	foreach($sizes as $size_name => $size_width){
		if($width >=  $size_width){
		$newwidth = $size_width;
		$newheight=($height/$width) * $newwidth;
		}else{
			$newwidth=$width;
			$newheight=$height;
		}
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		
		$filename = $target_path.$randomsha.'_'.$size_name.'.jpg';
		$didcreate = imagejpeg($tmp,$filename,70);
		imagedestroy($tmp);

		//save the filepath into the DB for the logged in user
		$query = "UPDATE users
					SET $size_name = '$filename'
				 	WHERE user_id = $user_id";
		//run it
		$result = $db->query($query);
		//make sure it worked
		if( $db->affected_rows >= 1 ):
			$statusmsg .= 'Your image was added to the DB.';
		else:
			$statusmsg .= 'Problem adding the image to the DB.';
		endif;
	}
	
	imagedestroy($src);
	
		
	}else{//width and height not greater than 0
		$didcreate = false;
	}
	
	
	if($didcreate) {
		$statusmsg .=  "The file ".  basename( $_FILES['uploadedfile']['name']). 
		" has been uploaded <br />";
	} else{
		$statusmsg .= "There was an error uploading the file, please try again!<br />";
	}		

endif; //did_upload
?>

<h2>Edit Your Profile</h2>
<?php 
if( isset($statusmsg)):
	echo $statusmsg;
endif; ?>
<form action="<?php $_SERVER['PHP_SELF'] ?>?page=profile" method="post" enctype="multipart/form-data">

	<label for="uploadedfile">Choose an Image (.jpg, .gif, .png)</label>
	<input type="file" name="uploadedfile" id="uploadedfile" />

	<input type="submit" value="Change User Pic" />
	<input type="hidden" name="did_upload" value="1" />

</form>

<h2>Your Current Avatar:</h2>
<?php echo show_avatar( $user_id, $db ); ?>