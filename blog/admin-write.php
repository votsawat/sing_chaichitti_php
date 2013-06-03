<?php  
//if the user submitted the form, parse it 
if( 1 == $_POST['did_post'] ):
	//extract and sanitize data
	$title = clean_input( $_POST['title'], $db );
	$body = clean_input( $_POST['body'], $db );
	$category = clean_input( $_POST['category'], $db );
	$is_public = clean_input( $_POST['is_public'], $db );
	$allow_comments = clean_input( $_POST['allow_comments'], $db );

	//convert unchecked checkboxes to 0 instead of null
	if( '' == $is_public ){
		$is_public = 0;
	}
	if('' == $allow_comments ){
		$allow_comments = 0;
	}

	//validate
	$valid = true;
	//if the body or title are blank, the form is invalid
	if( '' == $title OR '' == $body ):
		$valid = false;
		$msg = 'Title and body are required.';
	endif;

	//if the form is valid, GO! add the post to the DB
	if( true == $valid ):
		$query_insert = "INSERT INTO posts
						( title, body, category_id, date, is_public, allow_comments, user_id )
						VALUES 
						( '$title', '$body', $category, now(), $is_public, $allow_comments, $user_id )";
		
		//run it
		$result_insert = $db->query($query_insert);	
		//check to make sure one row was added
		if( 1 == $db->affected_rows ):
			$msg = 'Your post was successfully saved.';
		else:
			$msg = 'Something went wrong when saving your post.';
		endif; //one row added

	endif;//valid form
endif; //did_post

?>
<h2>Write New Post</h2>

<?php  
//if the msg exists, show it
if( isset($msg) ):
	echo '<div class="message">'.$msg.'</div>';
endif;
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>?page=write" method="post" novalidate="true">
	
	<div class="panel twothirds noborder">
		<label for="title">Title</label>
		<input type="text" name="title" id="title" required="required" />

		<label for="body">Body of post:</label>
		<textarea name="body" id="body" required="required"></textarea>
	</div>

	<div class="panel onethird">
		<label for="category">Category:</label>
		<select name="category" id="category">
			<?php  
			//get all the categories in alphabetical order
			$query_cats ="SELECT *
							FROM categories
							ORDER BY name ASC";
			$result_cats = $db->query($query_cats);
			//check to make sure at least one category exists
			if( $result_cats->num_rows >= 1):
				//loop through the results
				while( $row_cats = $result_cats->fetch_assoc() ): ?>

					<option value="<?php echo $row_cats['category_id']; ?>">
						<?php echo $row_cats['name']; ?>
					</option>

				<?php  
				endwhile;
			endif;
			?>
		</select>

		<input type="checkbox" name="is_public" id="is_public" value="1" />
		<label for="is_public">Make this post public</label>

		<input type="checkbox" name="allow_comments" id="allow_comments" value="1" />
		<label for="allow_comments">Allow comments on this post</label>

		<input type="submit" value="Save Post" />
	</div>
	<input type="hidden" name="did_post" value="1" />
</form>