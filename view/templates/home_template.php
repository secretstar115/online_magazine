

<script>
$(function() {
	var radio = $( "#radio" );
	radio.buttonset();
	radio.css("display", "inline");
    
    $('#articles').on("change", function(event){
    	 $("#LatestArticles").show();
         $("#LatestColumnArticles").hide();
         $("#LatestReviews").hide();
         radio.buttonset("refresh");
  });
    $('#ColumnArticles').on("change", function(event){
    	$("#LatestArticles").hide();
        $("#LatestColumnArticles").show();
        $("#LatestReviews").hide();
        radio.buttonset("refresh");
  });
    $('#reviews').on("change", function(event){
    	$("#LatestArticles").hide();
        $("#LatestColumnArticles").hide();
        $("#LatestReviews").show();
        radio.buttonset("refresh");
  });

    $("#LatestArticles").show();
    $("#LatestColumnArticles").hide();
    $("#LatestReviews").hide();
    radio.buttonset("refresh");
});

</script>
<h3>Popular:</h3>
<div class="slider">
	<div id="headline">
		<img src="<?php echo ROOT.$PopularArticles[0]->getImage() ?>"
			class="sidebar_image">
		<div class="overlay">
			<a
				href="<?php echo ROOT."/article/view/".$PopularArticles[0]->getId()?>">
				<h1><?php echo $PopularArticles[0]->getTitle() ?></h1>
			</a>
		</div>
		<div class="text">
			<p><?php
			echo implode ( ' ', array_slice ( explode ( ' ', $PopularArticles [0]->getBody () ), 0, 30 ) ) . "...";
			?><a
					href="<?php echo ROOT. "/article/view/".$PopularArticles [0]->getId() ?>">Read
					more</a>
			</p>
		</div>
	</div>
	<div id="other_articles">
		<div id="top">
			<div class="small_thumbnail">
				<img src="<?php echo ROOT.$PopularArticles[1]->getImage() ?>"
					class="sidebar_image">
				<div class="overlay">
					<a
						href="<?php echo ROOT."/article/view/".$PopularArticles[1]->getId()?>">
						<h2><?php echo $PopularArticles[1]->getTitle() ?></h2>
					</a>
				</div>
			</div>
			<div class="small_thumbnail">
				<img src="<?php echo ROOT.$PopularArticles[2]->getImage() ?>"
					class="sidebar_image">
				<div class="overlay">
					<a
						href="<?php echo ROOT."/article/view/".$PopularArticles[2]->getId()?>">
						<h2><?php echo $PopularArticles[2]->getTitle() ?></h2>
					</a>
				</div>
			</div>
		</div>
		<div id="bottom">
			<div class="small_thumbnail">
				<img src="<?php echo ROOT.$PopularArticles[3]->getImage() ?>"
					class="sidebar_image">
				<div class="overlay">
					<a
						href="<?php echo ROOT."/article/view/".$PopularArticles[3]->getId()?>">
						<h2><?php echo $PopularArticles[3]->getTitle() ?></h2>
					</a>
				</div>
			</div>
			<div class="small_thumbnail">
				<img src="<?php echo ROOT.$PopularArticles[4]->getImage() ?>"
					class="sidebar_image">
				<div class="overlay">
					<a
						href="<?php echo ROOT."/article/view/".$PopularArticles[4]->getId()?>">
						<h2><?php echo $PopularArticles[4]->getTitle() ?></h2>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="bottom_panels">
	<div id="featured" class="headlines_panel">
		<h3>Featured Articles</h3>
		<hr color="#5A8039" size="1px" />
		<ul>
<?php
foreach ( $FeaturedArticles as $article ) {
	echo "<li><h1><a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . $article->getTitle () . "</a></h1></li>";
}
?>
</ul>
	</div>


	<div id="recent_cat" class="headlines_panel">
		<h3>Latest Articles</h3>
		<hr color="#5A8039" size="1px" />
		<div id="radio">
			<label for="articles">Articles</label><input type="radio"
				id="articles" name="radio" checked="checked" /><label
				for="ColumnArticles">Column Articles</label> <input type="radio"
				id="ColumnArticles" name="radio" /> <label for="reviews">Reviews</label><input
				type="radio" id="reviews" name="radio" />
		</div>
		<ul id="LatestArticles">
<?php
foreach ( $LatestArticles as $article ) {
	echo "<li><h1><a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . $article->getTitle () . "</a></h1></li>";
}
?>
</ul>
		<ul id="LatestColumnArticles">
<?php
foreach ( $LatestColumnArticles as $article ) {
	echo "<li><h1><a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . $article->getTitle () . "</a></h1></li>";
}
?>
</ul>
		<ul id="LatestReviews">
<?php
foreach ( $LatestReviews as $article ) {
	echo "<li><h1><a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . $article->getTitle () . "</a></h1></li>";
}
?>
</ul>
	</div>
</div>
