<?php
require_once 'control/controller.php';
require_once 'models/mappers/comment_mapper.php';
/**
 * The Article Controller provides access to the individual pieces of the magazine. 
 * It also provides functionality for users. 
 *
 */
class ArticleController extends Controller {
	/**
	 * Show an individual article. 
	 * 
	 * @param unknown $id the id of the article
	 */
	function view($id) {
		$id = str_replace ( "/", "", $id );
		$id = ltrim ( $id, '0' );
		$this->_model->fetchArticleById ( $id );
	}
	/**
	 * Like an article. Subscriber functionality. 
	 * 
	 * @param unknown $id the id of the article
	 */
	function like($id) {
		$id = str_replace ( "/", "", $id );
		$id = ltrim ( $id, '0' );
		$this->_model->likeArticle ( $id );
		header ( 'Location: ' . ROOT . "/article/view/" . $id );
	}
	/**
	 * Dislike an article
	 * 
	 * @param unknown $id
	 */
	function dislike($id) {
		$id = str_replace ( "/", "", $id );
		$id = ltrim ( $id, '0' );
		$this->_model->dislikeArticle ( $id );
		header ( 'Location: ' . ROOT . "/article/view/" . $id );
	}
	/**
	 * Unlike an article. Subscriber functionality. 
	 * 
	 * @param unknown $id the id of the article
	 */
	function unlike($id) {
		$id = str_replace ( "/", "", $id );
		$id = ltrim ( $id, '0' );
		$this->_model->unlikeArticle ( $id );
		header ( 'Location: ' . ROOT . "/article/view/" . $id );
	}
	/**
	 * Stop disliking an article
	 * 
	 * @param unknown $id the id of the article
	 */
	function undislike($id) {
		$id = str_replace ( "/", "", $id );
		$id = ltrim ( $id, '0' );
		$this->_model->undislikeArticle ( $id );
		header ( 'Location: ' . ROOT . "/article/view/" . $id );
	}
	/**
	 * Comment on an article. Subscriber functionality. 
	 * 
	 * @param unknown $id the id of the article 
	 */
	function comment($id) {
		if (!empty($_POST ['commentbody'])) {
			$body = $_POST ['commentbody'];
			if (isset ( $_POST ['internal'] ) && $_POST ['internal'] == "internal") {
				$internal = 1;
			} else {
				$internal = 0;
			}
			// echo $internal;
			$data = array (
					"date" => date ( 'Y-m-d H:i:s' ),
					"user_id" => $_SESSION ['UserId'],
					"body" => $body,
					"article_id" => $id,
					"edit_comment" => $internal 
			);
			$this->_model->submitComment ( $data );
		} else {
			addMessage ( "error", "Comment is empty." );
		}
		header ( 'Location: ' . ROOT . "/article/view/" . $id );
	}
	/**
	 * Set an article to under review. Editor functionality. 
	 * 
	 * @param unknown $article_id the id of the article 
	 */
	function toggle_review($article_id) {
		$this->_model->setUnderReview ( $article_id );
		header ( 'Location: ' . ROOT . "/article/view/" . $article_id );
	}
	/**
	 * Update the status of an article. Editor functionality. 
	 * 
	 * @param unknown $article_id
	 */
	function update_status($article_id) {
		$new_status = $_POST ["status"];
		$this->_model->updateStatus ( $article_id, $new_status );
		header ( 'Location: ' . ROOT . "/article/view/" . $article_id );
	}
	/**
	 * Feature an article to the home page. Editor functionality. 
	 * 
	 * @param unknown $article_id
	 */
	function feature($article_id) {
		$this->_model->featureArticle ( $article_id );
		header ( 'Location: ' . ROOT . "/article/view/" . $article_id );
	}
	/**
	 * Remove an article from home page. Editor functionality. 
	 * 
	 * @param unknown $article_id
	 */
	function unfeature($article_id) {
		$this->_model->unFeatureArticle ( $article_id );
		header ( 'Location: ' . ROOT . "/article/view/" . $article_id );
	}
}