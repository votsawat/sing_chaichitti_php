<?php 
		//db host, username, password, database name
$db = new mysqli( 'localhost', 'votsawat_wp310', 'sing2821283', 'blog_vc_0902' );

//if there is an error connecting, kill the page
if( $db->connect_errno > 0 ){
	die('Unable to connect to the database');
	
}