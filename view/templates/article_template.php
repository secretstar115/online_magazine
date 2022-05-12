
<script>
$( document ).ready(function() {
	$("#edit_panel a[href]").button();
	$("#user_metadata a[href]").button();
});
</script>

<div class="contents">
 <?php
	displayMessage ( array (
			"like",
			"unlike",
			"dislike", 
			"undislike",
			"comment",
			"update_status",
			"feature",
			"unfeature" 
	) );
	
	if ($Article && $Article->visibleArticle ()) {
		echo "<h2>" . $Article->getTitle () . "</h2>";
		echo "<p>";
		if ($Article->getType () == "column_article") {
			echo "<b>Column: </b>" . $Article->getColumnName () . " ";
		} elseif ($Article->getType () == "review") {
			echo "<b>Reviw with rating: </b>" . $Article->getRating () . " ";
		} else {
			echo "<b>Article </b> ";
		}
		echo "<b>Written by:</b>" . htmlspecialchars ( $Article->getWriter () ) . " on ";
		echo "" . $Article->getDate () . ".";
		echo "<b> Keywords: </b>" . htmlspecialchars ( $Article->getKeyWords () ) . "; <b>Likes:</b> ";
		echo "" . htmlspecialchars ( $Article->getLikesCount () ) . "  <b>Dislikes:</b>";
		echo "" . htmlspecialchars ( $Article->getDislikesCount () ) . "</p>";
		echo "<hr color=\"#5A8039\" size=\"1px\" />";
		echo "<div id='panel'>";
		echo "<img src=\"" . ROOT . $Article->getImage () . "\">";
		if ((isEditor () && $Article->getStatus () == "submitted")) {
			echo "<div id='edit_panel'>";
			echo "<h3>Article Edit Options: </h3>";
			echo "<small><a href=\"" . ROOT . "/article/toggle_review/" . $Article->getId () . "\">Set article to 'Under review'</a></small>";
			echo "</div>";
		} elseif (isEditor () && ($Article->isUnderReview () || $Article->isAwaitingChanges ())) {
			echo "<div id='edit_panel'>";
			echo "<h3>Article Edit Options: </h3>";
			require_once 'edit_form.php';
			echo "<b>Current Status:</b></br>" . $Article->getFormattedStatus ();
			require_once 'article_status_form.php';
			echo "</div>";
		} elseif ($Article->checkIfWriter () && $Article->getStatus () == "awaiting_changes") {
			echo "<div id='edit_panel'>";
			echo "<h3>Article Edit Options: </h3>";
			require_once 'edit_form.php';
			echo "</div>";
		} elseif (isEditor () && ($Article->isPublished ())) {
			echo "<div id='edit_panel'>";
			echo "<h3>Article Edit Options: </h3>";
			if ($Article->getFeatured () == "0") {
				echo "<small><a href=\"" . ROOT . "/article/feature/" . $Article->getId () . "\">Feature article</a></small>";
			} else {
				echo "<small><a href=\"" . ROOT . "/article/unfeature/" . $Article->getId () . "\">Remove from featured</a></small>";
			}
			echo "</div>";
		} elseif (isEditor ()) {
			echo "<div id='edit_panel'>";
			echo "<h3>Article Options: </h3>";
			if ($Article->getFeatured () == "0") {
				echo "<small><a href=\"" . ROOT . "/article/feature/" . $Article->getId () . "\">Feature article</a></small>";
			} else {
				echo "<small><a href=\"" . ROOT . "/article/unfeature/" . $Article->getId () . "\">Remove from featured</a></small>";
			}
			echo "</div>";
		}
		echo "</div>";
		echo "<div id='article_body'>";
		if ($Article->getType () == "column") {
			echo "<p>" . $Column . "</p>";
		}
		
		echo "<p>" . $Article->getBody () . "</p>";
		echo "</div>";
		echo "<div id='user_metadata'>";
		echo "<p>" . "<b>Likes:</b> " . $Article->getLikesCount () . " <b>Dislikes:</b> " . $Article->getDislikesCount () . "</p>";
		if (isSubscriber ()) {
			if (!is_string($CanLike)) {
				echo "<small><a href=\"" . ROOT . "/article/like/" . $Article->getId () . "\">" . "Like " . "</a></small>";
				echo "<small><a href=\"" . ROOT . "/article/dislike/" . $Article->getId () . "\">" . "Dislike " . "</a></small>";
			} else {
				if ($CanLike == "dislike") {
					echo "<small><a href=\"" . ROOT . "/article/undislike/" . $Article->getId () . "\">" . "Undislike " . "</a></small>";
				} else if ($CanLike == "like") {					
					echo "<small><a href=\"" . ROOT . "/article/unlike/" . $Article->getId () . "\">" . "Unlike " . "</a></small>";
				}
			}
		}
		
		foreach ( $Comments as $key => $Comment ) {
			$comment_tag = "Comment";
			if ($Comment->isEditComment ()) {
				if (! ($Article->checkIfWriter () || isEditor ())) {
					continue;
				} else {
					$comment_tag = "Internal " . $comment_tag;
				}
			}
			echo "<div class=\"comment\"><p><b>" . $comment_tag . ":</b> " . htmlspecialchars ( $Comment->getBody (), ENT_COMPAT | ENT_SUBSTITUTE, "UTF-8" ) . "</p>";
			echo "<p><b>Written by: </b>" . $Users [$key]->getName () . " <b>on</b> " . $Comment->getDate () . "</p>";
			echo "</div>";
		}
		
		if (isSubscriber ()) {
			?>

<form id="comment_form" method="post"
		action="<?php echo ROOT."/article/comment/".$Article->getId () ?>"
		name="comment" id="comment">
		<textarea name="commentbody" id="commentbody"
			class="text ui-widget-content ui-corner-all" rows="5" cols="70"></textarea>
		<?php
			
			if (($Article->checkIfWriter () || isEditor ()) && ($Article->isUnderReview () || $Article->isAwaitingChanges ())) {
				echo "<input type='checkbox' name='internal' value='internal'>Tick to make comment internal (won't be seen outside the publishing process)";
			}
			?>
		<input type="submit" name="submit" id="submit" value="Submit comment" />
	</form>

<?php
		} else {
		}
	} else {
		echo "<h3>No such article or unavailable to you</h3>";
	}
	?>
	</div>
</div>
