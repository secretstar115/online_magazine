<?php
require_once 'db_connect.php';
require_once 'article.php';
/**
 * Provides a mapping for getting, creating and updating article data.
 */
class ArticleMapper {
	private $_connection;
	function __construct(DBConnect $connection) {
		$this->_connection = $connection;
	}
	
	// Internal
	/**
	 * Internal method used to map a sql results row to a class.
	 *
	 *
	 * @param unknown $result        	
	 * @param string $class        	
	 * @return multitype:
	 */
	private function _mapResultsToArticles($result, $class = "Article") {
		$output = [ ];
		while ( $row = $result->fetch () ) {
			array_push ( $output, new $class ( $row ) );
		}
		return $output;
	}
	
	/* ---- GET methods ----- */
	
	/**
	 * Fetch all articles from a table and return their mappings.
	 *
	 *
	 * @param string $table
	 *        	the target table, defaults to our global view for published articles
	 * @param string $class
	 *        	the target class, defaults to article
	 * @return Ambigous <multitype:, multitype:> array of $class
	 */
	public function fetchAllArticles($table = "global_published_articles_view", $class = "Article") {
		$result = $this->_connection->selectAllFrom ( $table );
		return $this->_mapResultsToArticles ( $result, $class );
	}
	/**
	 * Fetch an array of all submitted articles.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:> the array of articles
	 */
	function fetchAllSubmittedArticles() {
		return $this->fetchAllArticles ( "submitted_articles_view" );
	}
	/**
	 * Fetch the top popular articles.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:> an array of the 5 most popular articles
	 */
	function fetchPopularArticles() {
		return $this->fetchAllArticles ( "popular_articles_view" );
	}
	/**
	 * Fetch the latest highlighted articles to display on the home page.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:>
	 */
	function fetchFeaturedArticles() {
		return $this->fetchAllArticles ( "recently_highlighted_articles_view" );
	}
	/**
	 * Fetch all the articles, ordered by date.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:>
	 */
	function fetchAllLatestArticles() {
		return $this->fetchAllArticles ( "global_latest_articles_view" );
	}
	/**
	 * Fetch the latest 5 articles.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:>
	 */
	function fetchLatestFiveArticles() {
		return $this->fetchAllArticles ( "latest_articles_view" );
	}
	/**
	 * Fetch the latest 5 reviews.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:>
	 */
	function fetchLatestFiveReviews() {
		return $this->fetchAllArticles ( "latest_reviews_view", "Review" );
	}
	/**
	 * Fetch the latest 5 Column Articles.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:>
	 */
	function fetchLatestFiveColunArticles() {
		return $this->fetchAllArticles ( "latest_column_articles_view", "ColumnArticle" );
	}
	/**
	 * Fetch all column articles.
	 *
	 *
	 * @param unknown $column        	
	 * @return Ambigous <multitype:, multitype:>
	 */
	function fetchAllColumnArticles($column) {
		$result = $this->_connection->selectAllFromWhere ( "global_column_articles_view", "column_name=:column_name", array (
				":column_name" => $column 
		) );
		return $this->_mapResultsToArticles ( $result, "ColumnArticle" );
	}
	/**
	 * Fetch all reviews.
	 *
	 *
	 * @return Ambigous <Ambigous, multitype:, multitype:>
	 */
	function fetchAllReviews() {
		return $this->fetchAllArticles ( "global_reviews_view", "Review" );
	}
	/**
	 * Fetch an article by id.
	 *
	 *
	 * @param unknown $id
	 *        	the id of the article
	 * @return ColumnArticle Review Article NULL
	 */
	public function getArticleById($id) {
		$response = $this->_connection->selectById ( "global_articles_view", $id );
		try {
			$ar = $response->fetch ();
			switch ($ar ["type"]) {
				case "column_article" :
					$ar ['column_name'] = $this->_getColumnOfArticle ( $id );
					return new ColumnArticle ( $ar );
					break;
				
				case "review" :
					$ar ['rating'] = $this->_getRatingOfArticle ( $id );
					return new Review ( $ar );
					break;
				
				default :
					return new Article ( $ar );
					break;
			}
		} catch ( Exception $e ) {
			return null;
		}
	}
	/**
	 * Get all articles written by a writer.
	 *
	 *
	 * @param String $user_name
	 *        	the user name of the writer
	 * @return Ambigous <multitype:, multitype:> an array of articles written by the writer
	 */
	public function getUserArticles($user_name) {
		$result = $this->_connection->selectAllFromWhere ( "global_articles_view", "writers like :wname", array (
				":wname" => "%" . $user_name->getName () . "%" 
		) );
		return $this->_mapResultsToArticles ( $result );
	}
	/**
	 * Get the column of an article (the article is a column article)
	 *
	 * @param unknown $article_id
	 *        	the article id
	 * @return string the column
	 */
	private function _getColumnOfArticle($article_id) {
		$result = $this->_connection->selectAllFromWhere ( "column_article", "article_id=:article_id", array (
				":article_id" => $article_id 
		) );
		$row = $result->fetch ();
		return $row ["column_name"];
	}
	/**
	 * Get a rating for an article (the article is a review)
	 *
	 * @param unknown $article_id        	
	 * @return mixed
	 */
	private function _getRatingOfArticle($article_id) {
		$result = $this->_connection->selectAllFromWhere ( "ratings", "article_id=:article_id", array (
				":article_id" => $article_id 
		) );
		$row = $result->fetch ();
		return $row ["rating"];
	}
	/**
	 * Get the edit history of an editor.
	 *
	 * @param unknown $user_id
	 *        	the id of the editor
	 * @return Ambigous <multitype:, multitype:> an array of articles edited by the editor
	 */
	function getEditHistoryOfUser($user_id) {
		$result = $this->_connection->selectAllFromWhere ( "edits_view", "user_id=:user_id", array (
				":user_id" => $user_id 
		) );
		return $this->_mapResultsToArticles ( $result );
	}
	
	/*   ---- SUBMIT or UPDATE methods ----- */
	
	/**
	 * Submit a new article to the databases
	 * 
	 * @param array $data the data of the article
	 * @return Integer the article id of the newly submitted article
	 */
	public function submitNewArticle($data) {
		$writers = $data ["writer"];
		$keywords;
		if (! empty ( $data ["keywords"] )) {
			$keywords = $data ["keywords"];
		}
		unset ( $data ["writer"] );
		unset ( $data ["keywords"] );
		$this->_connection->_buildInsertQuery ( $data, "articles" );
		/* echo $sql; */
		/* $this->query ( $sql );  */
		$article_id = $this->lastInsertId ();
		if (! empty ( $keywords )) {
			$this->_submitContentKeywordData ( $article_id, $keywords );
		}
		$this->_submitContentWriterData ( $article_id, $writers );
		return $article_id;
	}
	/**
	 * Update an article
	 * 
	 * @param unknown $data the updated data
	 * @param unknown $article_id the article id
	 * @return unknown the article id 
	 */
	function updateArticle($data, $article_id) {
		// var_dump ( $data );
		if (! empty ( $data ["keywords"] )) {
			$keywords = $data ["keywords"];
		}
		unset ( $data ["keywords"] );
		$writers = $data ["writer"];
		unset ( $data ["writer"] );
		$this->_connection->_buildUpdateQuery ( $data, "articles", array (
				"id" => $article_id 
		) );
		$this->_deleteKeywords ( $article_id );
		$this->_deleteWriters ( $article_id );
		if (! empty ( $keywords )) {
			$this->_submitContentKeywordData ( $article_id, $keywords );
		}
		$this->_submitContentWriterData ( $article_id, $writers );
		$this->_storeEditInformation ( $article_id, $_SESSION ["UserId"] );
		return $article_id;
	}
	/**
	 * Update the edit history table in the database - store edit information. 
	 * 
	 * @param unknown $article_id the id of the article that is being edited
	 * @param unknown $editor_id the id of the editor that's editing the article 
	 */
	private function _storeEditInformation($article_id, $editor_id) {
		$this->_connection->_buildInsertQuery ( array (
				"article_id" => $article_id,
				"user_id" => $editor_id,
				"date_edited" => date ( 'Y-m-d H:i:s' ) 
		), "article_edits", array (
				"date_edited" => ":date_edited" 
		) );
	}
	/**
	 * Delete all the writers of an article. This is needed, since an editor 
	 * might have changed the writers of an submission. 
	 * 
	 * @param unknown $article_id the id of the article 
	 */
	private function _deleteWriters($article_id) {
		$this->_connection->deleteStatement ( "article_writers", array (
				"article_id" => $article_id 
		) );
	}
	/**
	 * Delete an article's keywords, this is needed since an editor might have removed
	 * keywords
	 * 
	 * @param unknown $article_id the id of the article
	 */
	private function _deleteKeywords($article_id) {
		$this->_connection->deleteStatement ( "article_keywords", array (
				"article_id" => $article_id 
		) );
	}
	/**
	 * Perform an update on a Column Article
	 * 
	 * @param array $data the data of the updated article
	 * @param Integer $article_id
	 */
	function updateColumnArticle($data, $article_id) {
		$column = $data ["column_article"];
		unset ( $data ["column_article"] );
		$article_id = $this->updateArticle ( $data, $article_id );
		$this->_connection->_buildInsertQuery ( array (
				"article_id" => $article_id,
				"column_name" => $column 
		), "column_article", array (
				"column_name" => ":column_name" 
		) );
	}
	/**
	 * Perform an update on a Review
	 * 
	 * @param unknown $data
	 * @param unknown $article_id
	 */
	function updateReview($data, $article_id) {
		$rating = $data ["rating"];
		unset ( $data ["rating"] );
		$article_id = $this->updateArticle ( $data, $article_id );
		$this->_connection->_buildInsertQuery ( array (
				"article_id" => $article_id,
				"rating" => $rating 
		), "ratings", array (
				"rating" => ":rating" 
		) );
	}
	/**
	 * Like an article
	 * 
	 * @param unknown $id
	 */
	public function likeArticle($id) {
		$response = $this->_connection->_buildInsertQuery ( array (
				"article_id" => $id,
				"user_id" => getCurrUserId()
		), "likes" );
	}
	/**
	 * Dislike an article
	 * 
	 * @param unknown $id
	 */
	public function dislikeArticle($id) {
		$response = $this->_connection->_buildInsertQuery ( array (
				"article_id" => $id,
				"user_id" => getCurrUserId(), 
				"is_like"=>"0"
		), "likes" );
	}
	/**
	 * Unlike an article
	 * 
	 * @param unknown $id
	 */
	public function unlikeArticle($id) {
		// var_dump ( $_SESSION ['UserId'] );
		$this->_connection->deleteStatement ( "likes", array (
				"article_id" => $id,
				"user_id" => getCurrUserId() 
		) );
	}
	/**
	 * Undislike an article
	 * 
	 * @param unknown $id
	 */
	public function undislikeArticle($id) {
		$this->_connection->deleteStatement ( "likes", array (
				"article_id" => $id,
				"user_id" => getCurrUserId() 
		) );
	}
	/**
	 * Set an article's status to under review. 
	 * 
	 * @param unknown $article_id the id of the target article
	 */
	public function setUnderReview($article_id) {
		$this->updateArticleStatus ( $article_id, "under_review" );
	}
	/**
	 * Update an article's status. 
	 * 
	 * @param unknown $article_id the id of the target article
	 * @param unknown $status the new status
	 */
	public function updateArticleStatus($article_id, $status) {
		$sql = $this->_connection->_buildUpdateQuery ( array (
				"status" => $status 
		), "articles", array (
				"id" => $article_id 
		) );
	}
	/**
	 * Submit a new Review
	 * 
	 * @param array $data the data of the review
	 */
	function submitReview($data) {
		// var_dump ( $data );
		$rating = $data ["rating"];
		unset ( $data ["rating"] );
		$article_id = $this->_connection->submitNewArticle ( $data );
		$this->_addRating ( $article_id, $rating );
	}
	/**
	 * Add information to the ratings table for a Review.
	 * 
	 * @param Integer $article_id the id of the review
	 * @param Integer $rating the rating
	 */
	private function _addRating($article_id, $rating) {
		$sql = $this->_connection->_buildInsertQuery ( array (
				"article_id" => $article_id,
				"rating" => $rating 
		), "ratings" );
	}
	/**
	 * Submit a new Column Article
	 * 
	 * @param array $data the data of the column article
	 */
	function submitColumnArticle($data) {
		// var_dump ( $data );
		$column = $data ["column_article"];
		unset ( $data ["column_article"] );
		$article_id = $this->submitNewArticle ( $data );
		$this->_addColumnData ( $article_id, $column );
	}
	/**
	 * Add information to the column table for a column article.
	 * 
	 * @param Integer $article_id the id of the column article
	 * @param String $column the column
	 */
	private function _addColumnData($article_id, $column) {
		$this->_connection->_buildInsertQuery ( array (
				"article_id" => $article_id,
				"column_name" => $column 
		), "column_article" );
	}
	/**
	 * Submit writer data for an article 
	 * 
	 * @param unknown $article_id the id of the article
	 * @param unknown $writers the writers
	 */
	private function _submitContentWriterData($article_id, $writers) {
		foreach ( $writers as $writer ) {
			$sql = $this->_connection->_buildInsertQuery ( array (
					"article_id" => $article_id,
					"writer_id" => $writer 
			), "article_writers", array (
					"writer_id" => ":writer_id" 
			) );
		}
	}
	/**
	 * Add keywords for an article 
	 * 
	 * @param unknown $article_id
	 * @param unknown $keywords
	 */
	private function _submitContentKeywordData($article_id, $keywords) {
		foreach ( $keywords as $keyword ) {
			$sql = $this->_connection->_buildInsertQuery ( array (
					"article_id" => $article_id,
					"keyword" => $keyword 
			), "article_keywords", array (
					"keyword" => ":keyword" 
			) );
		}
	}
	/**
	 * Feature an article. 
	 * 
	 * @param unknown $article_id the id of the article
	 */
	function setFeaturedArticle($article_id) {
		$sql = $this->_connection->_buildUpdateQuery ( array (
				"featured" => "1" 
		), "articles", array (
				"id" => $article_id 
		) );
	}
	/**
	 * Remove an article from featured.
	 * 
	 * @param unknown $article_id the id of the article
	 */
	function unsetFeaturedArticle($article_id) {
		$sql = $this->_connection->_buildUpdateQuery ( array (
				"featured" => "0" 
		), "articles", array (
				"id" => $article_id 
		) );
	}
}