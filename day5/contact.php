<?php 
include_once('functions.php');

//parse the form if it was submitted
if( $_POST['did_submit'] ):
	//extract user submitted data
	$name = filter_var( $_POST['name'], FILTER_SANITIZE_STRING );
$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
$phone = filter_var( $_POST['phone'], FILTER_SANITIZE_NUMBER_INT );

	//allow certain tags in the message, strip all others
$message = strip_tags( trim($_POST['message']), '<b><i><strong><em><p>' );
$newsletter = $_POST['newsletter'];

	//validate !
$valid = true;

	//check for empty name field
if( 0 == strlen($name) ){
	$valid = false;
	$error['name'] = 'Please fill out your name.';
}
	//check for invalid email format
if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
	$valid = false;
	$error['email'] = 'Please provide a valid email address.';
}
	//check for message too long
if( strlen( $message ) > 250 ) {
	$valid = false;
	$error['message'] = 'Make sure your message is less than 250 characters.';
}


if( 1 == $newsletter ): 
	$newsletter = 'YES!';
else:
	$newsletter = 'NO!';
endif;

	//only send the mail if the form was valid
if( true == $valid ):

	//get ready to send mail - set up mail() parameters
	$to = 'votsawat@gmail.com';
$subject = 'Contact form from wp310 class demo';

$body = "Name: $name \n";
$body .= "Email: $email \n";
$body .= "Phone: $phone \n\n";
$body .= "Add to Newsletter List? $newsletter \n\n";
$body .= "Message: $message \n\n";


$header = "Reply-to: $email \r\n";
$header .= "From: $name \r\n";

	//send it did_send will equal 1 if the mail sends, 0 if it fails to send
$did_send = mail( $to, $subject, $body, $header );

	//handle success/failure user feedback
if( $did_send ):
	$display_msg = '<div class="thanks">Thank you for your message, '.$name.', I will get back to you soon.</div>';
else:
	$display_msg = 'Sorry, there was a problem sending your message. ';

		endif; //did_send

	endif; //still valid

endif; //did_submit

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>Example Contact Form - Simple </title>

	<link rel="stylesheet" type="text/css" href="format_contact.css">


</head>


<body>

	<div id="form">
		<header>
			<h1>Contact Me</h1>			
		</header>

		<?php 
		if( isset($display_msg) ):
			echo $display_msg;

		endif;
		?>


		<?php 
//hide the form if it sent successfully
		if( !$did_send ): ?>


		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<label for="name">Your Name:</label>
			<input type="text" name="name" id="name" value="<?php sticky_field($name); ?>" />
			<?php display_error( $error, 'name'); ?>

			<label for="email">Your Email Address:</label>
			<input type="text" name="email" id="email" value="<?php sticky_field($email); ?>" />
			<?php display_error( $error, 'email'); ?>

			<label for="phone">Your Phone Number:</label>
			<input type="text" name="phone" id="phone" value="<?php sticky_field($phone); ?>" />

			<label for="message">Your Message:</label>
			<textarea name="message" id="message"><?php sticky_field($message); ?></textarea>
			<?php display_error( $error, 'message'); ?>

			<input type="checkbox" name="newsletter" value="1" id="newsletter" <?php checked( 'YES!', $newsletter ); ?> />
			<label for="newsletter">I would like to receive the newsletter</label>

			<input type="submit" value="Send Message">
			<input type="hidden" name="did_submit" value="1" />

		</form>
	</div>

<?php endif; //hide form if did_send ?>

</body>
</html>
