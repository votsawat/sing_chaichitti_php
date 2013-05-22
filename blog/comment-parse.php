<?php 
//confirm that the form was submitted
if( 1 == $_REQUEST['did_comment'] ):
	//extract and sanitize
	$name = clean_input($_REQUEST['name'], $db); 
	$email = clean_input($_REQUEST['email'], $db); 
	$url = clean_input($_REQUEST['url'], $db); 
	$comment = clean_input($_REQUEST['comment'], $db); 

	//begin validation
	$valid = true;

	//check for blank name field
	if( 0 == strlen($name) ):
		$valid = false;
		$message = 'Please fill out your name.<br />';
	endif;

	//check for valid email
	if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ):
		$valid = false;
		$message .= 'Provide a valid email address. <br />';
	endif;

	//check for blank comment
	if( 0 == strlen($comment) ):
		$valid = false;
		$message .= 'Please fill out your comment.';
	endif;

	//if the form is still valid, add the comment to the DB
	if( true == $valid ):
		//set up the query to add one comment
		$query_insert = "INSERT INTO comments
						( name, email, url, date, body, post_id, is_approved )
						VALUES
						( '$name', '$email', '$url', now(), '$comment', $post_id, 1 )";
			//run it
			$result_insert = $db->query($query_insert);

			//check to make sure one row was added
			if( 1 == $db->affected_rows ):
				$message .= 'Thank you for your comment!';
			else:
				$message .= 'Something went wrong when submitting your comment. Try again.';
			
			endif; //one row added

	endif; //form is valid

endif; //form was submitted