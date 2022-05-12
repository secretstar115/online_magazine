
<div class="member_panel">
<?php
if (empty ( $_SESSION ['LoggedIn'] ) || empty ( $_SESSION ['Username'] )) {
	echo "<h2>You will soon be redirected to the login page</h2>";
	echo "<meta http-equiv='refresh' content='2;" . ROOT . "' >";
} else {
	echo "<h4>Welcome to the member area " . $_SESSION ['UserType'] . " " . $_SESSION ['Username'] . " !</h4>";
}

displayMessage ( array (
		"submit",
		"edit",
		"login",
		"login_check",
		"register",
		"create_user",
		"promote_user" 
) );

if (isWriter ()) {
	// echo "<a href=". ROOT."/write>Write a new article</a>";
	echo "<div class='writer_panel contents'>";
	echo "<h2>Writer Panel</h2>";
	echo '<hr color="#5A8039" size="1px" />';
	echo "<h3>Your articles: </h3>";
	echo "<p>You will not be able to view your submissions until they are published. </p>";
	echo "<table> <tr><th>Title</th><th>Date of submission</th><th>Status</th></tr>";
	foreach ( $UserArticles as $article ) {
		echo "<tr><td class=\"article\">";
		echo "<a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . htmlspecialchars ( $article->getTitle () ) . "</a>";
		echo "</td><td>";
		echo $article->getDateTime ();
		echo "</td><td><b>";
		if ($article->getStatus () == "submitted") {
			echo "Submitted";
		} elseif ($article->getStatus () == "awaiting_changes") {
			echo "Awaiting changes, go to article page";
		} elseif ($article->getStatus () == "under_review") {
			echo "Under review";
		} elseif ($article->getStatus () == "published") {
			echo "Published";
		} elseif ($article->getStatus () == "rejected") {
			echo "Rejected";
		}
		
		echo "</b></td></tr>";
	}
	echo "</table>";
	require_once 'write_form.php';
	echo "</div>";
}
if (isEditor ()) {
	echo "<div class='editor_panel contents'>";
	echo "<h2>Editor Panel</h2>";
	echo '<hr color="#5A8039" size="1px" />';
	echo "<h3>Submitted articles waiting to be reviewed </h3>";
	echo "<p>Visit those articles in order to edit them or feature them on the homepage. </p>";
	echo "<table> <tr><th>Title</th><th>Date of submission</th><th>Status</th></tr>";
	foreach ( $SubmittedArticles as $article ) {
		echo "<tr><td class=\"article\">";
		echo "<a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . htmlspecialchars ( $article->getTitle () ) . "</a>";
		echo "</td><td>";
		echo $article->getDateTime ();
		echo "</td><td><b>";
		if ($article->getStatus () == "submitted") {
			echo "Submitted";
		} elseif ($article->getStatus () == "awaiting_changes") {
			echo "Sent for changes to writer";
		} elseif ($article->getStatus () == "under_review") {
			echo "Under review";
		} elseif ($article->getStatus () == "published") {
			echo "Published";
		}
		echo "</b></td></tr>";
	}
	echo "</table>";
	
	echo "<h3>Your edit History: </h3>";
	echo '<hr color="#5A8039" size="1px" />';
	echo "<table> <tr><th>Title</th><th>Date of your last edit</th><th>Status</th></tr>";
	foreach ( $EditHistory as $article ) {
		echo "<tr><td class=\"article\">";
		echo "<a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . htmlspecialchars ( $article->getTitle () ) . "</a>";
		echo "</td><td>";
		echo $article->getDateTime ();
		echo "</td><td><b>";
		if ($article->getStatus () == "submitted") {
			echo "Submitted";
		} elseif ($article->getStatus () == "awaiting_changes") {
			echo "Sent for changes to writer";
		} elseif ($article->getStatus () == "under_review") {
			echo "Under review";
		} elseif ($article->getStatus () == "published") {
			echo "Published";
		} elseif ($article->getStatus () == "rejected") {
			echo "Rejected";
		}
		echo "</b></td></tr>";
	}
	echo "</table>";
	
	echo "<h3>Current featured articles:</h3>";
	echo '<hr color="#5A8039" size="1px" />';
	echo "<p>Featured articles as seen on the home page.</p>";
	echo "<table> <tr><th>Title</th><th>Date of article submission</th></tr>";
	foreach ( $FeaturedArticles as $article ) {
		echo "<tr><td class=\"article\">";
		echo "<a href=\"" . ROOT . "/article/view/" . $article->getId () . "\">" . htmlspecialchars ( $article->getTitle () ) . "</a>";
		echo "</td><td>";
		echo $article->getDate ();
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
}

if (isPublisher ()) {
	echo "<div class='publisher_panel contents'>";
	echo "<h2>Publisher Panel</h2>";
	echo '<hr color="#5A8039" size="1px" />';
	echo "<h3>Magazine users: </h3>";
	echo "<table border=\"1\"> <tr><th>User</th><th>Type</th></tr>";
	foreach ( $NonPublishers as $user ) {
		echo "<tr><td class=\"article\">" . $user->getName () . "</td><td>" . $user->getType () . "</td>";
	}
	echo "</table>";
}
?>
<?php

if (isPublisher ()) {
	?>
	<h3>Subscriber management:</h3>
	<p>You can promote a subscriber to become a writer or editor.</p>
	<form method="post" action="/IAPT1/member/promote_user"
		name="promoteform" id="promoteform">
		<fieldset>
			<label for="user">Name</label><select name="user" id="user">
				<?php
	foreach ( $NonPublishers as $user ) {
		if ($user->getType () == "subscriber") {
			echo "<option value=\"" . $user->getId () . "\">" . $user->getName () . "</option>";
		}
	}
	?>
			</select><label for="newtype">Promote User</label> <select
				name="newtype" id="newtype">
				<option value="writer">Writer</option>
				<option value="editor">Editor</option>
			</select> <input type="submit" name=submit id="submit" value="Submit" />
		</fieldset>
	</form>
</div>
</div>
<?php
} else {
}

?>
