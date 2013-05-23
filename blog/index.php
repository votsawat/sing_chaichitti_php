<?php require( 'db_connect.php' ); 
include_once( 'functions.php' ); ?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Votsawat's PHP Blog</title>
<link rel="stylesheet" type="text/css" href="blog.css">
<link rel="alternate" type="application/rss+xml" href="rss.php" title="RSS Feed of Blog Posts">

</head>

<body>
	<div id="container">

	<header>
		<h1><a href="index.php" class="home">Votsawat's blog</a></h1>
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
		case 'single':
			include( 'content-single.php' );
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
