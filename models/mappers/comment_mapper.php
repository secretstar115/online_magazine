<?php
require_once 'db_connect.php';
require_once 'comment.php';
/**
 * The Comment Mapper provides mappings from the database to Comment objects. 
 */
class CommentMapper {
	private $_connection;
	function __construct($connection) {
		$this->_connection = $connection;
	}
	/**
	 * Get all comments for an article. 
	 * 
	 * @param unknown $id the id of the article we're getting comments for. 
	 * @return multitype: an array of Comment objects 
	 */
	public function getCommentsByArticleId($id) {
		$response = $this->_connection->selectById ( "comments", $id, "article_id" );
		$output = [ ];
		while ( $row = $response->fetch () ) {
			try {
				array_push ( $output, new Comment ( $row ) );
			} catch ( Exception $e ) {
				echo $e->getMessage ();
			}
		}
		return $output;
	}
	/**
	 * Add a new comment for an article. 
	 * 
	 * @param unknown $data the data of the comment
	 */
	public function submitComment($data) {
		$sql = $this->_connection->_buildInsertQuery ( $data, "comments" );
	}
}