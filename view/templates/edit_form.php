
<script src="<?php echo JS_PATH ."/edit_form.js" ?>"> </script>

<?php
function selectOption($value, $target) {
	if ($value == $target) {
		echo "selected";
	}
}
function hasKeyword($keyword, $article) {
	if (strpos ( $article->getKeyWords (), $keyword ) !== FALSE)
		return true;
	else
		return false;
}
function hasWriter($writer, $article) {
	if (strpos ( $article->getWriter (), $writer ) !== FALSE)
		return true;
	else
		return false;
}
echo "<script>$( document ).ready(function() {";
switch ($Article->getType ()) {
	case "article" :
		echo "\$('.rating_select').hide();";
		echo "\$('.column_select').hide();";
		break;
	
	case "column_article" :
		echo "\$('.rating_select').hide();";
		echo "\$('.column_select').show();";
		break;
	
	case "review" :
		echo "\$('.column_select').hide();";
		echo "\$('.rating_select').show();";
		break;
}

echo "});</script>";

?>

<div id="article-edit-form" title="Edit article">
	<p class="validateTips">All form fields are required.</p>
	<form method="post"
		action="<?php echo ROOT. "/member/edit/".$Article -> getId() ?>"
		name="editform" id="submitform" enctype="multipart/form-data">
		<fieldset>
			<label for="title">Title</label> <input type="text"
				name="article[title]" id="title"
				class="text ui-widget-content ui-corner-all"
				<?php echo "value=\"".$Article->getTitle(). "\"" ?> /> <label
				for="file">Select new Image:</label> <input type="file" name="file"
				id="file"> <label for="type">Article Type:</label> <select
				name="article[type]" id="column_article">
				<option value="article"
					<?php
					selectOption ( "article", $Article->getType () )?>>Article</option>
				<option value="column_article"
					<?php
					
					selectOption ( "column_article", $Article->getType () )?>>Column
					Article</option>
				<option value="review"
					<?php
					
					selectOption ( "review", $Article->getType () )?>>Review</option>
			</select> <label for="column" class="column_select">Column:</label> <select
				name="article[column_article]" class="column_select">
				<option value="tech">Technology</option>
				<option value="cs_success">CS Success</option>
			</select> <label for="rating" class="rating_select">Rating:</label> <select
				name="article[rating]" class="rating_select">
				<?php
				if ($Article->getType () == "review") {
					foreach ( range ( 0, 5 ) as $rating ) {
						echo "<option value='" . $rating . "'" . selectOption ( $rating, $Article->getRating () ) . ">" . $rating . "</option>";
					}
				} else {
					foreach ( range ( 0, 5 ) as $rating ) {
						echo "<option value='" . $rating . "'>" . $rating . "</option>";
					}
				}
				?>
			</select> <label for="keywords" class="keywords">Keywords:</label> <select
				name="article[keywords][]" class="keywords" multiple>
				<?php
				foreach ( unserialize ( KEYWORDS ) as $key => $display ) {
					if (hasKeyword ( $key, $Article )) {
						echo "<option value=\"" . $key . "\" selected>" . $display . "</option>";
					} else {
						echo "<option value=\"" . $key . "\">" . $display . "</option>";
					}
				}
				?>
			</select> <label for="writers" class="writers">Other Writers:</label>
			<select name="article[writer][]" id="writers" class="writers" multiple>
	  <?php
			foreach ( $WriterList as $writer ) {
				if (hasWriter ( $writer->getName (), $Article )) {
					echo "<option value=\"" . $writer->getId () . "\" selected>" . $writer->getName () . "</option>";
				} else {
					echo "<option value=\"" . $writer->getId () . "\">" . $writer->getName () . "</option>";
				}
			}
			?>
	</select> <label for="body">Article Body</label>
			<textarea name="article[body]" id="body"
				class="text ui-widget-content ui-corner-all" rows="15" cols="50"><?php echo $Article->getBody() ?></textarea>
		</fieldset>
	</form>
</div>

<button id="edit-article">Edit article</button>

<div id="dialog">Are you sure that you want to submit this article?</div>
