
<div class="contents">
	<h3>Articles in Column <?php echo $Column ?></h3>
<?php
foreach ( $ColumnData as $article ) {
	echo "<div class=\"article_headline\">";
	echo "<h3><a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . $article->getTitle () . "</a></h3>";
	echo "<p><b>Written by:</b>" . htmlspecialchars ( $article->getWriter () ) . " on ";
	echo "" . (new DateTime ( $article->getDate () ))->format ( 'Y-m-d' ) . ".";
	echo "<b> Keywords: </b>" . htmlspecialchars ( $article->getKeyWords () ) . "; <b>Likes:</b> ";
	echo "" . htmlspecialchars ( $article->getLikesCount () ) . "</p>";
	echo "</div>";
}
?>
</div>