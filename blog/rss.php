<?php
require( 'db_connect.php' );

function convTimestamp($date){
  $year   = substr($date,0,4);
  $month  = substr($date,5,2);
  $day    = substr($date,8,2);
  $hour   = substr($date,11,2);
  $minute = substr($date,14,2);
  $second = substr($date,17,2);
  $stamp =  date('D, d M Y H:i:s O', mktime($hour, $min, $sec, $month, $day, $year));
  return $stamp;
}


echo '<?xml version="1.0" encoding="utf-8" ?>'; ?>
<rss version="2.0">
	<channel>
		<title>Votsawat Blog</title>
		<link>http://localhost/sing_chaichitti_php/blog/</link>
		<description>A blog about nothing</description>
		<image>
			<title>Votsawat's PHP Blog</title>
			<url>http://localhost/sing_chaichitti_php/blog/images/screenshot.png</url>
			<link>http://localhost/sing_chaichitti_php/blog/</link>
		</image>

<?php  
//set up query to get 10 public posts, newest first
$query = "SELECT posts.*, users.username, users.email
			FROM posts, users
			WHERE posts.is_public = 1
			AND posts.user_id = users.user_id
			ORDER BY posts.date DESC
			LIMIT 10 ";

//run it
$result = $db->query($query);
//check to make sure at least one result came back

if( $result->num_rows >= 1 ):
	while( $row = $result->fetch_assoc() ):
?>
		<item>
			<title><?php echo $row['title']; ?></title>
			<link>http://localhost/sing_chaichitti_php/blog/index.php?page=single&amp;post_id=<?php echo $row['post_id']; ?></link>
			<guid>http://localhost/sing_chaichitti_php/blog/index.php?page=single&amp;post_id=<?php echo $row['post_id']; ?></guid>
			<pubDate><?php echo convTimestamp($row['date']); ?></pubDate>
			<author><?php echo $row['email'] ?> (<?php echo $row['username']; ?>)</author>
			<description><?php echo $row['body']; ?></description>
		</item>

<?php 
	endwhile; //while there is row to show
endif; //at least one post ?>

	</channel>
</rss>