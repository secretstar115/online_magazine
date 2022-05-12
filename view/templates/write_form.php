


<script src="<?php echo JS_PATH ."/write_form.js" ?>"> </script>

<div id="article-form" title="Create new article">
	<p class="validateTips">All form fields are required.</p>
	<form method="post" action="/IAPT1/member/submit" name="submitform"
		id="submitform" enctype="multipart/form-data">
		<fieldset>
			<label for="title">Title</label> <input type="text"
				name="article[title]" id="title"
				class="text ui-widget-content ui-corner-all" /> <label for="file">Image:</label>
			<input type="file" name="file" id="file"> <label for="type">Article
				Type:</label> <select name="article[type]" id="column_article">
				<option value="article">Article</option>
				<option value="column_article">Column Article</option>
				<option value="review">Review</option>
			</select> <label for="column" class="column_select">Column:</label> <select
				name="article[column_article]" class="column_select">
				<option value="tech">Technology</option>
				<option value="cs_success">CS Success</option>
			</select> <label for="rating" class="rating_select">Rating:</label> <select
				name="article[rating]" class="rating_select">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select> <label for="keywords" class="keywords">Keywords:</label> <select
				name="article[keywords][]" class="keywords" multiple>
				<?php
				foreach ( unserialize ( KEYWORDS ) as $key => $display ) {
					echo "<option value=\"" . $key . "\">" . $display . "</option>";
				}
				?>
			</select> <label for="writers" class="writers">Other Writers:</label>
			<select name="article[writer][]" class="writers" multiple>
	  <?php
			foreach ( $WriterList as $writer ) {
				echo "<option value=\"" . $writer->getId () . "\">" . $writer->getName () . "</option>";
			}
			?>
	</select> <label for="body">Article Body</label>
			<textarea name="article[body]" id="body"
				class="text ui-widget-content ui-corner-all" rows="15" cols="50"></textarea>
		</fieldset>
	</form>
</div>

<button id="create-article">Create a new article</button>

<div id="dialog">
  Are you sure that you want to submit this article? 
</div>
