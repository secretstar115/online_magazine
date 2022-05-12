<?php
require_once 'models/model.php';
require_once 'mappers/article_mapper.php';
require_once 'mappers/comment_mapper.php';
require_once 'mappers/user_mapper.php';
require_once 'models/mappers/db_connect.php';

/**
 * The Article Model contains functions for updating the model of an individual article.
 */
class ArticleModel extends Model {
	function __construct($view) {
		parent::__construct ( $view );
		// Connect to database
		$connection = new DBConnect ();
		// Create the mappers
		$this->articleMapper = new ArticleMapper ( $connection );
		$this->commentMapper = new CommentMapper ( $connection );
		$this->userMapper = new UserMapper ( $connection );
	}
	/**
	 * Fetch an article by article id
	 *
	 * @param unknown $article_id
	 *        	the id of the article
	 */
	function fetchArticleById($article_id) {
		$article = $this->articleMapper->getArticleById ( $article_id );
		$this->set ( "Article", $article );
		$Comments = $this->commentMapper->getCommentsByArticleId ( $article_id );
		if (! empty ( $Comments )) {
			$this->set ( "Users", $this->userMapper->getAuthorsOfComments ( $Comments ) );
		}
		$this->set ( "Comments", $Comments );
		if (isSubscriber ()) {
			$like = $this->userMapper->userHasLiked ( $article_id, getCurrUserId () );
			if (! is_string ( $like )) {
				$this->set ( "CanLike", true );
			} else {
				$this->set ( "CanLike", $like );
			}
		} else {
			$this->set ( "CanLike", false );
		}
		if (isWriter ()) {
			$this->set ( "WriterList", $this->userMapper->getAllOtherWriters () );
		}
	}
	/**
	 * Like an article.
	 * Update the model.
	 *
	 * @param unknown $id        	
	 */
	function likeArticle($id) {
		try {
			$this->articleMapper->likeArticle ( $id );
			addMessage ( "success", "Liked article." );
		} catch ( DBException $e ) {
		}
	}
	/**
	 * Dislike an article.
	 *
	 *
	 * @param unknown $id        	
	 */
	function dislikeArticle($id) {
		try {
			$this->articleMapper->dislikeArticle ( $id );
			addMessage ( "success", "Disliked article." );
		} catch ( DBException $e ) {
		}
	}
	/**
	 * Stop liking an article. 
	 * 
	 * @param unknown $id
	 */
	function unlikeArticle($id) {
		try {
			$this->articleMapper->unlikeArticle ( $id );
			addMessage ( "success", "Unliked article." );
		} catch ( DBException $e ) {
		}
	}
	/**
	 * Stop disliking an article. 
	 * 
	 * @param unknown $id
	 */
	function undislikeArticle($id) {
		try {
			$this->articleMapper->undislikeArticle ( $id );
			addMessage ( "success", "Undisliked article." );
		} catch ( DBException $e ) {
		}
	}
	/**
	 * Add a comment to an article. 
	 * 
	 * @param unknown $data
	 */
	function submitComment($data) {
		try {
			$this->commentMapper->submitComment ( $data );
			addMessage ( "success", "Successfully submitted comment." );
		} catch ( DBException $e ) {
			addMessage ( "error", "Could not submit comment. " );
		}
	}
	/**
	 * Set an article's status to under review. 
	 * 
	 * @param unknown $article_id
	 */
	function setUnderReview($article_id) {
		$this->articleMapper->setUnderReview ( $article_id );
	}
	/**
	 * Update an article's status. 
	 * 
	 * @param unknown $article_id the id of the article
	 * @param unknown $status the new status 
	 */
	function updateStatus($article_id, $status) {
		try {
			$this->articleMapper->updateArticleStatus ( $article_id, $status );
			addMessage ( "success", "Article is now " . $status . "." );
		} catch ( DBException $e ) {
			addMessage ( "error", "Could not submit comment. " );
		}
	}
	/**
	 * Feature an article on the home page.
	 * 
	 * @param unknown $article_id the id of the article
	 */
	function featureArticle($article_id) {
		try {
			$this->articleMapper->setFeaturedArticle ( $article_id );
			addMessage ( "success", "Article is now featured on the homepage." );
		} catch ( DBException $e ) {
			addMessage ( "error", "Could not feature article. " );
		}
	}
	/**
	 * Remove an article from featured. 
	 * 
	 * @param unknown $article_id
	 */
	function unFeatureArticle($article_id) {
		try {
			$this->articleMapper->unsetFeaturedArticle ( $article_id );
			addMessage ( "success", "Article is now removed from featured." );
		} catch ( DBException $e ) {
			addMessage ( "error", "Could not unfeature article. " );
		}
	}
}