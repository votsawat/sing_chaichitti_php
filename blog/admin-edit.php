<?php  
//which post are we editing?
$post_id = $_GET['post_id'];

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
		$query_update = "UPDATE posts
						SET
						title = '$title',
						body = '$body',
						category_id = $category,
						is_public = $is_public,
						allow_comments = $allow_comments
						WHERE post_id = $post_id ";
						
		//run it
		$result_update = $db->query($query_update);	
		//check to make sure one row was added
		if( 1 == $db->affected_rows ):
			$msg = 'Your post was successfully saved.';
		else:
			$msg = 'No changes were made.';
		endif; //one row added

	endif;//valid form
endif; //did_post

//Prefill the form with the values for THIS post
$query_post = "SELECT *
				FROM posts
				WHERE post_id = $post_id
				AND user_id = $user_id
				LIMIT 1";
$result_post = $db->query($query_post);

//make sure we found a result
if( 1 <= $result_post->num_rows ):
	$row_post = $result_post->fetch_assoc();

?>
<h2>Edit Post</h2>

<?php  
//if the msg exists, show it
if( isset($msg) ):
	echo '<div class="message">'.$msg.'</div>';
endif;
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>?page=edit&amp;post_id=<?php echo $post_id; ?>" method="post" novalidate="true">
	
	<div class="panel twothirds noborder">
		<label for="title">Title</label>
		<input type="text" name="title" id="title" required="required" value="<?php echo $row_post['title']; ?>" />

		<label for="body">Body of post:</label>
		<textarea name="body" id="body" required="required"><?php echo $row_post['body']; ?></textarea>
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

					<option value="<?php echo $row_cats['category_id']; ?>" <?php
					//if the category being displayed matches the category of THIS post
					if( $row_post['category_id'] == $row_cats['category_id'] ):
						echo 'selected="selected"';
					endif;
					?>>
						<?php echo $row_cats['name']; ?>
					</option>

				<?php  
				endwhile;
			endif;
			?>
		</select>

		<input type="checkbox" name="is_public" id="is_public" value="1" <?php 
		//if the value of is_public is 1
		if( $row_post['is_public'] == 1 ):
			echo 'checked="checked"';
		endif;
		?> />
		<label for="is_public">Make this post public</label>

		<input type="checkbox" name="allow_comments" id="allow_comments" value="1" <?php 
		//if the value of allow_comments is 1
		if( $row_post['allow_comments'] == 1 ):
			echo 'checked="checked"';
		endif;
		?> />
		<label for="allow_comments">Allow comments on this post</label>

		<input type="submit" value="Save Post" />
	</div>
	<input type="hidden" name="did_post" value="1" />
</form>

<?php  
else:
	echo '<h2>You do not have permission to edit this post.</h2>';
endif;
?>