<?php 
//Configuration
$perpage = 5; //number of results to show per page
$pagenum = 1; // set the starting page number

//what phrase did they search for?
$phrase = $_GET['phrase'];

//get all posts and comments that are similar to the query, make sure the posts are distinct
$query_search = "SELECT distinct *
  				FROM posts
				WHERE is_public = 1
				AND ( title LIKE '%".$phrase."%'
				OR body LIKE '%".$phrase."%' )
				ORDER BY date DESC";
//run it	   
$result_search = $db->query($query_search);
//check to see if anything came back from the query
if ($result_search->num_rows >= 1){
	//setup some defaults for pagination
	if(isset($_GET['pagenum'])){ //if the URL contains a page number 
		$pagenum = $_GET['pagenum'];  //set the page number
	}
	
	//CALCULATIONS FOR PAGINATION
	//total results from search
	$totalhits = $result_search->num_rows; 
	
	//figure out the last page in the results (number of times we can divide the results by the number of posts per page, round up to account for any remainder - so, if we find 11 posts and have 5 per page, we will need 3 pages)
	$maxPage = ceil($totalhits/$perpage); 
	
	//check to see if the user is trying to view a valid page of results
	if($pagenum <= $maxPage and $totalhits > 0){ 
 
		// counting the offset
		$offset = ($pagenum - 1) * $perpage;
		
		//concatenate the LIMIT onto the original query
		//offset = which record to start at (first record is 0)
		//perpage = how many records to show after that point
		$query_modified = $query_search . " LIMIT $offset, $perpage";
		
		//run the modified query
		$modresults = $db->query($query_modified);
		
		//pagination buttons
		$check_next = $totalhits - ($pagenum * $perpage);
		//if there are more pages ahead, show the button
		if ($check_next > 0){			
			$nextpage = $pagenum + 1;
			$next = '<a class="button" name="next_page" id="next_page" href="index.php?page=search&amp;phrase='.$phrase.'&amp;pagenum='.$nextpage.'"><span>Next Page</span></a>';
		}else{ //otherwise show a disabled button
			$next = '<span class="disabled button"  id="next_page">Next Page</span>';
		}
	
		$check_prev = $offset - $perpage;
		//if there are previous pages to show, show a button
		if ($check_prev >= 0){
			$prevpage = $pagenum - 1;
			$prev = '<a class="button" name="previous_page" href="index.php?page=search&amp;phrase='.$phrase.'&amp;pagenum='.$prevpage.'" id="previous_page"><span>Previous Page</span></a>';
		}else{ //otherwise, show a disabled button
			$prev = '<span class="disabled button" id="previous_page">Previous Page</span>';
		}
		
//############## Search Result Display ##################
 
?>
 
<h2>Search Results ( <?php echo $totalhits; ?> )</h2>
<h3>Showing page <?php echo $pagenum; ?> of <?php echo $maxPage; ?></h3>
<section id="search_results_list">
	<?php
//show each article 
		while ($row = $modresults->fetch_assoc()){		

			//count the comments on that post for display
			$postid = $row['post_id'];
			$query_count = "SELECT COUNT(*) AS numcomments FROM comments WHERE post_id=$postid";
			$result_count = $db->query($query_count);
			$row_count = $result_count->fetch_assoc();
			$numcomments = $row_count['numcomments'];
			?>
	<article class="post">
		<h3><a href="?page=single&amp;post_id=<?php echo $postid; ?>"><?php echo $row['title']; ?></a></h3>
		<div class="postmeta">Posted on <?php echo convert_date($row['date']); ?> | <?php comments_number($numcomments); ?></div>
	</article>
	<?php } ?>
	<?php
//end while, show footer
//end search results
?>
<footer class="search_footer"> 
	<h3>Showing page <?php echo $pagenum; ?> of <?php echo $maxPage; ?></h3>
	<span class="pagination"><?php echo $prev; ?> <?php echo $next; ?></span>
</footer>

</section>
<?php  
//##############  ERRORS  ##################
 
	}else{ //Uh oh, we went past the last page
		echo '<div class="message error">
			<h2>Page Limit Reached</h2>
			<p>You have gone beyond the available results limit for this search. In other words, there are no more results to see, try your search again.</p></div>';
	}
	
}else{ //No results to show
	echo '<div class="message error">
			<h2>No Matches Found</h2>
			<p>Try your search with a different phrase. This one turned up no results.</p></div>';
}
?>