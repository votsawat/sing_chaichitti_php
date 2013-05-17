<?php require('db_connect.php'); ?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Votsawat's PHP Blog</title>
<link rel="stylesheet" type="text/css" href="blog.css">

</head>

<body>
	<div id="container">
	<header>
		<h1>Votsawat's blog</h1>
		<nav>
			<ul>
				<li><a href="index.php">HOME</a></li>
				<li><a href="index.php?page=blog">BLOG</a></li>
				<li><a href="index.php?page=links">LINKS</a></li>
			</ul>
		</nav>
	</header>

	<div id="content">
	<main>
	<?php 
	//logic to load the correct page contents.
	//URI will look like domain/index.php?page=something
	switch( $_GET['page'] ){
		case 'blog':
			include( 'content-blog.php' );	
		break;
		case 'links':
			include( 'content-links.php' );	
		break;
		default:
			include( 'content-home.php' );
	}
	 ?>
	</main>

	<?php include('sidebar.php'); ?>


	<footer>
		<p>&copy; 2013 Platt College</p>
	</footer>
	</div>

</div>

</body>
</html>
