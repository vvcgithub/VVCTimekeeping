<div class = "widget">
	<h2>News</h2>
	<div class = "inner">
		<p><strong>Date: </strong><?php echo date("F j, Y", strtotime(news_date())); ?></p>
		<p><strong>Title: </strong><?php echo news_title(); ?></p>
		<p><strong>Description: </strong><?php echo news_description(); ?></p>
	</div>
</div>