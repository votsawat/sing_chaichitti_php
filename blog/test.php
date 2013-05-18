<?php  
	$query_link = "SELECT title, url, description
					FROM links
					LIMIT 5";
	if( $result_link = $db->query($query_link) ):
	?>

	<h2>Links</h2>
	<ul>
	<?php  
	while( $row_link = $result_link->fetch_assoc() ):?>
		<li><a href="<?php echo $row_link['url'] ?>" target="new"><?php echo $row_link['title'] ?>| 
		<?php echo $row_link['description'] ?></a></li>
	<?php endwhile; ?>
		<li><a href="http://www.smashingmagazine.com" target="new">Smashing Magazine</a></li>
	</ul>
	<?php endif; ?>

