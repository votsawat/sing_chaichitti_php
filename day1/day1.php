<?php
//creat some vars - possible values are 'success' or 'error'
$status = 'success';
$message = 'Hello World';

//Logic to control the message text
if( $status == 'success' ){
	$message = 'Your information was submitted successfully';
}else {
	$message = 'Sorry. Something went wrong.';
}

?>

<!DOCTYPE html>
<html>

<head>
	<style>
		.error {
			background-color: #F00;
		}
		.success{
			background-color: #0F0;
		}
	</style>
</head>

<body>
	
	<div class="<?php echo $status; ?>">

	<h2>
	<?php 
	// this is a secret 
	/*secret*/

	echo $message; ?>
	</h2>

</div>

</body>
</html>