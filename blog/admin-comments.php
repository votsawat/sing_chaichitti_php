<?php 
//parse comment deletion
if( $_POST['did_delete'] == 1 ):
	//collect a list of all the boxes they checked
	$delete_array = $_POST['delete'];
	//do nothing if the array is empty
	if( $delete_array != '' ):
		//go through the list of IDs, deleting comments one at a time
		foreach( $delete_array as $comment_id ){
			$query_delete = "DELETE FROM comments 
							WHERE comment_id = $comment_id";
			$result_delete = $db->query($query_delete);
		}
	endif; //empty array

endif;
?>

<h2>Manage Comments on Your Posts</h2>

<section class="panel">
	
	<?php //get the title and post ID of all posts written by the logged in user, that have comments 
	$query_posts = "SELECT distinct posts.title, posts.post_id 
					FROM posts, comments
					WHERE posts.post_id = comments.post_id
					AND posts.user_id = $user_id";

	$result_posts = $db->query($query_posts);
	//check to see if there is at least one row in the result
	if( $result_posts->num_rows >= 1 ):
	?>

	<form action="" method="post">

		<?php while( $row_posts = $result_posts->fetch_assoc() ): ?>
		<h3><?php echo $row_posts['title']; ?></h3>

		<?php //make a handy variable for this post_id
		$post_id = $row_posts['post_id'];
		//get all the comments about THIS post
		$query_comm = "SELECT * 
						FROM comments
						WHERE post_id = $post_id
						ORDER BY date ASC";

		//run it
		$result_comm = $db->query($query_comm);
		?>
		<ul>
		<?php while( $row_comm = $result_comm->fetch_assoc() ): ?>
			<li>
			<input type="checkbox" name="delete[<?php echo $row_comm['comment_id']; ?>]" value="<?php echo $row_comm['comment_id']; ?>" id="delete-<?php echo $row_comm['comment_id']; ?>" />
			<label for="delete-<?php echo $row_comm['comment_id']; ?>"><?php echo $row_comm['body']; ?>by <?php echo $row_comm['name']; ?></label>
			</li>
		<?php endwhile; //comments ?>

		</ul>
		<?php endwhile; //posts ?>

		<input type="submit" value="Delete Selected" class="warn" onclick="return confirmSubmit()" />
		<input type="hidden" name="did_delete" value="1" />
	</form>

<?php 
else:
	echo 'None of your posts have comments yet!';
endif; // there are posts to show ?>
</section>