<?php 
//open a new session or resume the existing session
session_start();
require('db_connect.php');
include_once('functions.php');

//if the user is already logged in, push them to their admin panel
if($_SESSION['logged_in']){
	header('Location:admin.php');
}


//if the form was submitted, try to log them in
if( $_POST['did_login'] ){
	//extract the values the user typed in and sanitize
	$orig_username = $_POST['username'];
	$orig_password = $_POST['password'];
	$clean_username = clean_input($_POST['username'], $db);
	$clean_password = clean_input($_POST['password'], $db);

	//apply a hash to the password
	$sha_password = sha1($clean_password);

	//check to see if minimum lengths are met (validate)
	if( strlen( $clean_username ) >= 5 AND strlen( $clean_password ) >= 5 ){

		//look for a user that matches in the DB
		$query = "SELECT user_id
					FROM users
					WHERE username = '$clean_username'
					AND password = '$sha_password'
					AND is_admin = 1
					LIMIT 1";
		$result = $db->query($query);

		//if one record is found, log them in
		if( 1 == $result->num_rows ){
			$row = $result->fetch_assoc();
			//use cookies and sessions to remember the user
			$_SESSION['logged_in'] = 1;
			setcookie( 'logged_in', 1, time() + 60 * 60 * 24 * 14 );
			//WHO is logged in?
			$_SESSION['user_id'] = $row['user_id'];
			setcookie( 'user_id', $row['user_id'], time() + 60 * 60 * 24 * 14 );
			//direct the now logged-in user to the admin panel
			header( 'Location:admin.php' );
		}else{
			$error = 1;
		}
	}else{ 
		//Username or pass too short
		$error = 1;
	}
}

//if the user is trying to log out, unset and destroy the session and cookies
if( $_GET['action'] == 'logout' ){
	unset( $_SESSION['logged_in'] );
	unset( $_SESSION['user_id'] );
	session_destroy();
	setcookie('logged_in', '', time() - 60 * 60 * 24 * 365 ); 
	setcookie('user_id', '', time() - 60 * 60 * 24 * 365 ); 
}
//if the user visits the page, and the cookie is still valid, re-create the session variable
elseif( $_COOKIE['logged_in'] == 1 ){
	$_SESSION['logged_in'] = 1;
	$_SESSION['user_id'] = $_COOKIE['user_id'];
}

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">

<link rel="stylesheet" type="text/css" href="format.css">

<title>Log in to your account </title>
</head>

<body>

	<?php 
	//if not logged in, show the form
	if( !$_SESSION['logged_in'] ){

	 ?>

<div id="form">

	<h1>Log in</h1>

	<?php 
	//if an error was triggered, show a message
	
	if( $error ){
		echo '<div class="error">Username and password do not match. Try again.</div>';
	} 
	?>


	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username">
		<br />

		<label for="password">Password:</label>
		<input type="password" name="password" id="password">
		<br />

		<input type="submit" value="Log In">
		<input type="hidden" name="did_login" value="1">

	</form>
</div>

<?php 
} //end if  not logged in
else{ ?>

	<p>you are logged in</p>
	<p><a href="login_cookie_session.php?action=logout">Log Out</a></p>

<?php }?>

<?php //echo $orig_username . '<br />' . $clean_username; ?>

</body>
</html>