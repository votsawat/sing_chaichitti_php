<aside>
	<?php 
	//set up query to get the titles & post_id of the latest 10 posts
	$query_latest = "SELECT title, post_id
					FROM posts
					WHERE is_public = 1
					ORDER BY date DESC
					LIMIT 10";
	//run query and check for results
	if( $result_latest = $db->query($query_latest) ):

	?>
	
	

	<h2>Latest Posts</h2>
	<ul>
		<?php 
		//from the list of results, go through each row, one at a time
		while( $row_latest = $result_latest->fetch_assoc() ): ?>
			<li><a href="#"><?php echo $row_latest['title']; ?></a></li>
		<?php endwhile; ?>

	</ul>
	<?php endif; ?>

	<?php 
	// set up query to get name and category_id
	$query_cate = "SELECT name, category_id 
					FROM categories
					LIMIT 5";
	//run query and check the results
	if( $result_cate = $db->query($query_cate) ):
	?>


	<h2>Categories</h2>
	<ul>
	<?php
	while( $row_cate = $result_cate->fetch_assoc() ):?>
		<li><a href="#"><?php echo $row_cate['name'] ?></a></li>
	<?php endwhile;  ?>

	</ul>
	<?php endif; ?>


	<?php  
	$query_link = "SELECT title, url
					FROM links
					LIMIT 5";
	if( $result_link = $db->query($query_link) ):
	?>

	<h2>Links I like:</h2>
	<ul>
	<?php  
	while( $row_link = $result_link->fetch_assoc() ):?>
		<li><a href="<?php echo $row_link['url'] ?>" target="new"><?php echo $row_link['title'] ?></a></li>
	<?php endwhile; ?>
	</ul>
	<?php endif; ?>				



</aside>