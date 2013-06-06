<?php 
session_start();
require('db_connect.php');
include_once('functions.php');

//if user is not logged in, get them away from this page
if( 1 != $_SESSION['logged_in']){
	header('Location:login.php');
}

//get all the info about the logged in user
$user_id = $_SESSION['user_id'];
$query_user = "SELECT *
				FROM users
				WHERE user_id = $user_id";
				
$result_user = $db->query($query_user);
$row_user = $result_user->fetch_assoc();
//handy variables we can use anywhere in our admin panel
$username = $row_user['username'];
$userpic = $row_user['avatar_link'];

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage your Blog</title>
<link rel="stylesheet" type="text/css" href="admin-style.css">
<script src="js/custom.js" type="text/javascript"></script>
</head>


<body>
<div id="wrapper">
	<header>
		<h1>Manage your Blog!</h1>
		<ul class="utilities">
			<li class="login">You are logged in as <?php echo $username; ?></li>
			<li class="logout"><a href="login.php?action=logout">Log Out</a></li>
			<li><a href="index.php">View Blog</a></li>
		</ul>

		<nav>
			<ul class="nav">
				<li><a href="admin.php">Dashboard</a></li>
				<li><a href="admin.php?page=write">Write Post</a></li>
				<li><a href="admin.php?page=manage">Manage Posts</a></li>
				<li><a href="admin.php?page=comments">Manage Comments</a></li>
				<li><a href="admin.php?page=profile">Edit Profile</a></li>
			</ul>
		</nav>
		
	</header>

	<main id="content">
		<?php 
		//get the correct content based on the 'page' the user is viewing
		switch ( $_GET['page']) {
			case 'write':
				include('admin-write.php');
			break;
			case 'manage':
				include('admin-manage.php');
			break;
			case 'edit':
				include('admin-edit.php');
			break;
			case 'comments':
				include('admin-comments.php');
			break;
			case 'profile':
				include('admin-profile.php');
			break;
			
			default:
				include('admin-dashboard.php');
		}
		?>
	</main>

	<footer>
		<small>&copy; 2013 Sing Chaichitti</small>
	</footer>
</div>
</body>
</html>
