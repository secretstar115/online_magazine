<?php
require_once 'object_map.php';
/**
 * Defines a Comment.
 * A comment is a communication of an user and is associated with an article.
 */
class Comment extends ObjectMap {
	protected $id;
	protected $date;
	protected $user_id;
	protected $body;
	protected $article_id;
	protected $edit_comment;
	public function getId() {
		return $this->id;
	}
	public function getDate() {
		return $this->date;
	}
	public function getUserId() {
		return $this->user_id;
	}
	public function getBody() {
		return $this->body;
	}
	public function getArticleId() {
		return $this->article_id;
	}
	public function getEditComment() {
		return $this->edit_comment;
	}
	/**
	 * Check if a comment is an internal comment.
	 * If a comment is internal it is only visible to editors
	 * and article writers.
	 *
	 * @return boolean
	 */
	public function isEditComment() {
		if ($this->getEditComment () == 1) {
			return true;
		} else {
			return false;
		}
	}
}