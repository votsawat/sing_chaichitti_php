<?php 
session_start();
require('db_connect.php');
include_once('functions.php');

//parse the form!
if( 1 == $_REQUEST['did_register'] ):
	//extract and clean all the data from the form
	$username = clean_input( $_REQUEST['username'], $db );
	$email = clean_input( $_REQUEST['email'], $db );
	$password = clean_input( $_REQUEST['password'], $db );
	$repassword = clean_input( $_REQUEST['repassword'], $db );	
	$policy = clean_input( $_REQUEST['policy'], $db );	

	//hashed version of the password
	$sha_password = sha1($password);

	//validate
	$valid = true;

	//make sure username is long enough
	if( strlen( $username ) < 5 ):
		$valid = false;
		$msg = 'Your username must be at least 5 characters long. <br />';
	else:
		//check to see if the username is already in use
		$query_username = "SELECT username 
							FROM users 
							WHERE username = '$username'
							LIMIT 1";
		$result_username = $db->query($query_username);
		//if one result is found, the username is TAKEN!
		if( $result_username->num_rows == 1 ):
			$valid = false;
			$msg .= 'The username you chose is already taken. Try another. <br />';
		endif;
	endif;

	//check for incorrect email pattern
	if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ):
		$valid = false;
		$msg .= 'Please provide a valid email address. <br />';
	else:
		//check to see if email is already in use
		$query_email = "SELECT email 
						FROM users
						WHERE email = '$email'
						LIMIT 1";
		$result_email = $db->query($query_email);
		//if one record found, email is TAKEN!
		if( $result_email->num_rows == 1 ):
			$valid = false;
			$msg .= 'The email address you chose is already in use. Did you mean to log in? <br />';
		endif;
	endif;

	//is the password too short
	if( strlen( $password ) < 5 ):
		$valid = false;
		$msg .= 'Your password must be at least 5 characters long. <br />';
	endif;

	//mismatched passwords
	if( $password != $repassword ):
		$valid = false;
		$msg .= 'The passwords provided do not match. <br />';
	endif;

	//unchecked policy box
	if( 1 != $policy ):
		$valid = false;
		$msg .= 'You must agree to the terms of service and privacy policy before becoming a member. <br />';
	endif;

	//if the form is still valid, GO! add this new user to the database
	if( true == $valid ):
		$query_insert = "INSERT INTO users
						( username, password, email, join_date, is_admin )
						VALUES
						( '$username', '$sha_password', '$email', now(), 1 )";
		$result_insert = $db->query($query_insert);
		//check to make sure one row was added
		if( 1 == $db->affected_rows ):
			//LOG THEM IN
			//use cookies and sessions to remember the user
			$_SESSION['logged_in'] = 1;
			setcookie( 'logged_in', 1, time() + 60 * 60 * 24 * 14 );
			$_SESSION['user_id'] = $db->insert_id;
			setcookie( 'user_id', $db->insert_id, time() + 60 * 60 * 24 * 14 );
			//direct the now logged-in user to the admin panel
			header( 'Location:admin.php' );
		else:
			$msg .= 'Something went wrong, try again.';
		endif; //query insert worked


	endif; //form is valid
endif; // did register

 ?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Sign up for an Account</title>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>

<body>
	<h1>Sign Up!</h1>
	
	<?php
	//error message reporting
	if(isset($msg)):
		echo $msg;
	endif; ?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<label for="username">Choose your Username:</label>
		<input type="text" name="username" id="username" />

		<label for="email">Email Address:</label>
		<input type="email" name="email" id="email" required="required" />

		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required="required" />

		<label for="repassword">Repeat Password:</label>
		<input type="password" name="repassword" id="repassword" required="required" />

		<br>
		<input type="checkbox" name="policy" id="policy" value="1" required="required" />
		<label for="policy">I Agree to the
			<a href="#">terms of service and privacy policy</a>
		</label>

		<input type="submit" value="Sign Up!" />
		<input type="hidden" name="did_register" value="1" />



	</form>

</body>

</html>
