<?php 
// @TODO: Validate this so if a user messes with the URL, it doesn't show a blank page 
//what post are we trying to show?
$post_id = $_GET['post_id'];

//set up a query to get the post that we are trying to view if it is public
		$query = "SELECT posts.*, categories.*, users.username, users.user_id
					FROM posts, categories, users
					WHERE posts.is_public = 1
					AND posts.category_id = categories.category_id
					AND posts.user_id = users.user_id
					AND posts.post_id = $post_id
					ORDER BY posts.date DESC
					LIMIT 1";
		//run it and check to make sure the result contains posts
		if( $result = $db->query($query) ):
		 ?>

		<?php 
		//loop through the list of results
		while( $row = $result->fetch_assoc() ):

		?>

		<article class="post">
			<h3><?php echo $row['title']; ?></h3>
			<div class="postmeta">Posted on <?php echo convert_date($row['date']); ?> 
				| in the category <?php echo $row['name']; ?>
				| By <?php echo $row['username']; ?>
			</div>
			<p><?php echo $row['body']; ?></p>
		</article>

<?php //get all the 'approved' commments on THIS post, oldest first
$query_comm = "SELECT name, date, body
				FROM comments
				WHERE is_approved = 1
				AND post_id = $post_id 
				ORDER BY date ASC"; 	

//check before if there is comment.
if( $result_comm = $db->query($query_comm) ):
	?>

<section id="comments">
	<h3><?php echo comments_number($result_comm->num_rows); ?> on this post</h3>

	<?php if( $result_comm->num_rows > 0 ): ?>
	<ol>
		<?php //loop through each comment
		while( $row_comm = $result_comm->fetch_assoc() ): ?>
		<li>
			<h4><?php echo $row_comm['name']; ?> says:</h4>
			<p><?php echo $row_comm['body']; ?></p>
			<time datetime="<?php echo $row_comm['date']; ?>">
				<?php echo convert_date($row_comm['date']); ?>
			</time>
		</li>
	<?php endwhile; 
	//set the comments the result free, because we are DONE with it.
	$result_comm->free();?>
	</ol>
	<?php else: ?>
	<h4> Your comment could be the first</h4>	
	<?php endif; //more than 0 results  ?>

</section>
<?php endif; //comment results found?>

		<?php 
		endwhile;
		?>
		

		<?php else: ?>

			<h2>No Posts to Show</h2>

		<?php endif; ?>
