<h2>Your Dashboard</h2>

<section class="onehalf panel">
	<h3>Your Stats</h3>
	<ul>
		<li>You have <?php echo count_posts($db, $user_id); ?> Published Posts</li>
		<li>You have <?php echo count_posts($db, $user_id, 2); ?> Posts Drafts</li>
		<li>There are <?php echo count_user_comments($db, $user_id); ?> Approved Comments</li>
		<li>There are <?php echo count_user_comments($db, $user_id, 2); ?> Comments awaiting moderation</li>
	</ul>
</section>

<section class="onehalf panel">
	<h3>Latest Comments</h3>
		<ul>
			<li>Comment!</li>
			<li>Comment!</li>
			<li>Comment!</li>
		</ul>
</section>