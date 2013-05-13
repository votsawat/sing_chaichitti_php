<?php 
//parse the form if it was submitted
if( $_POST['did_submit'] ):
	//extract user submitted data
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$message = $_POST['message'];
	$newsletter = $_POST['newsletter'];

	//todo: validation
	if( 1 == $newsletter ): 
		$newsletter = 'YES!';
	else:
		$newsletter = 'NO!';
	endif;

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
		$display_msg = 'Thank you for your message, '.$name.', I will get back to you soon.';
	else:
		$display_msg = 'Sorry, there was a problem sending your message. ';

	endif; //did_send

endif; //did_submit

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Example Contact Form - Simple </title>

<link rel="stylesheet" type="text/css" href="format.css">

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
<input type="text" name="name" id="name" />

<label for="email">Your Email Address:</label>
<input type="text" name="email" id="email" />

<label for="phone">Your Phone Number</label>
<input type="text" name="phone" id="phone" />

<label for="message">Your Message:</label>
<textarea name="message" id="message"></textarea>

<input type="checkbox" name="newsletter" value="1" id="newsletter" />
<label for="newsletter">I would like to receive the newsletter</label>

<input type="submit" value="Send Message">
<input type="hidden" name="did_submit" value="1" />

</form>
</div>

<?php endif; //hide form if did_send ?>

</body>
</html>
