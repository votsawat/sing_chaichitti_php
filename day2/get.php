<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Get Method Sample</title>
</head>

<body>
	<form method="get" action="get.php">
		<label for="name">What is your name?</label>
		<input type="text" name="name" id="name" />

		<label for="breakfast">what did you eat for breakfast?</label>
		<input type="text" name="breakfast" id="breakfast" />

		<input type="submit" value="GO!" />
		<input type="hidden" name="did_submit" value="1" />

	</form>

	<?php 
	//only show the message if the form wad submitted
	if($_GET['did_submit'] == 1 ){
		/*
		echo 'Good Morning, ';
		echo $_GET['name'];
		echo '. ';
		echo $_GET['breakfast'];
		echo ' Sounds delicious.';
		*/

		echo 'Good Morning, '.$_GET['name'].'.'.$_GET['breakfast']. 
		' Sounds delicious.';
	}
	?>

</body>

</html>
