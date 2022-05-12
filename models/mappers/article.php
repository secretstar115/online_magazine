<?php
require_once 'util/util.php';
require_once 'object_map.php';

/**
 * Class representation of an Article.
 * An article is a magazine piece, containing various information.
 */
class Article extends ObjectMap {
	protected $id;
	protected $title;
	protected $body;
	protected $image_path;
	protected $likes_count;
	protected $dislikes_count;
	protected $keywords;
	protected $date;
	protected $status;
	protected $type;
	protected $writers;
	protected $featured;
	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getBody() {
		return $this->body;
	}
	public function getImage() {
		return $this->image_path;
	}
	/**
	 * Get the likes count of this article
	 *
	 * @return string
	 */
	public function getLikesCount() {
		// var_dump($this->likes_count);
		if ($this->likes_count == "") {
			return '0';
		} else {
			return $this->likes_count;
		}
	}
	/**
	 * Get the dislikes count of this article
	 *
	 * @return string
	 */
	public function getDislikesCount() {
		// var_dump($this->likes_count);
		if ($this->dislikes_count == "") {
			return '0';
		} else {
			return $this->dislikes_count;
		}
	}
	public function getKeyWords() {
		return $this->keywords;
	}
	/**
	 * Get date of article
	 */
	public function getDate() {
		return (new DateTime ( $this->date ))->format ( 'Y-m-d' );
	}
	/**
	 * Get date and time of article
	 */
	public function getDateTime() {
		return (new DateTime ( $this->date ))->format ( 'Y-m-d @ H:i:s' );
	}
	public function getStatus() {
		return $this->status;
	}
	public function getWriter() {
		return $this->writers;
	}
	public function getType() {
		return $this->type;
	}
	public function getFeatured() {
		return $this->featured;
	}
	/**
	 * Check if the currently logged in user is the writer of this article.
	 *
	 * @return boolean true if the current user is the writer, false otherwise
	 */
	public function checkIfWriter() {
		// Refers to a globally defined method for getting the username of the currently logged in user
		$user = getCurrUsername ();
		if ($user) {
			return strpos ( $this->getWriter (), $user ) !== false;
		} else {
			return false;
		}
	}
	/**
	 * Return a formatted representation of the status, to be used when displaying the article
	 *
	 * @return string
	 */
	public function getFormattedStatus() {
		switch ($this->getStatus ()) {
			case "submitted" :
				return "Submitted";
			
			case "awaiting_changes" :
				return "Awaiting changes";
			
			case "under_review" :
				return "Under review";
			
			case "published" :
				return "Published";
			case "rejected" :
				return "Rejected";
		}
	}
	/**
	 * Check if an articl should be visible to a user
	 *
	 * @return boolean
	 */
	public function visibleArticle() {
		return $this->isPublished () || ($this->isAwaitingChanges () && $this->checkIfWriter ()) || isEditor ();
	}
	public function isSubmitted() {
		return $this->getStatus () == "submitted";
	}
	public function isUnderReview() {
		return $this->getStatus () == "under_review";
	}
	public function isAwaitingChanges() {
		return $this->getStatus () == "awaiting_changes";
	}
	public function isRejected() {
		return $this->getStatus () == "rejected";
	}
	public function isPublished() {
		return $this->getStatus () == "published";
	}
}
/**
 * Defines a ColumnArticle.
 * A Column article is simply an article that is associated with a column.
 */
class ColumnArticle extends Article {
	protected $column_name;
	function getColumnName() {
		return $this->column_name;
	}
}
/**
 * Defines a Review.
 * A Review is simply an article that is critiquing something with a rating.
 */
class Review extends Article {
	protected $rating;
	function getRating() {
		return $this->rating;
	}
}