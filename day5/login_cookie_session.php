<?php  
//open a new session or resume the existing session

session_start();

//the correct username/password combo

$correct_username = 'votsawat';
$correct_password = 'phprules';


//if the form was submitted, try to log them in
if( $_POST['did_login'] ){
	//extract the values the use typed in and sanitize
	$orig_username = $_POST['username'];
	$orig_password = $_POST['password'];
	$clean_username = strip_tags(trim($_POST['username']));
	$clean_password = strip_tags(trim($_POST['password']));

	//check user values with correct values. if they match, log them in
	if( strlen( $clean_username ) >= 5 AND strlen( $clean_password ) >= 5 ){

	//compare user values with DOCTYPE values. if they match, log them in

	if( $clean_username == $correct_username AND $clean_password == $correct_password ){
		//use cookies and sessions to remember the user
		$_SESSION['logged_in'] = 1;
		setcookie( 'login', 1, time() + 60 * 60 * 24 * 14 );
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
	session_destroy();
	setcookie('login', '', time() - 60 * 60 * 24 * 365 ); // make it to be expired since last year
}

//if the user visits the page, and the cookie is still valid, re-create the session variable
elseif( $_COOKIE['login'] == 1) {
	$_SESSION['logged_in'] = 1;
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


	<form method="post" action="login_cookie_session.php">
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