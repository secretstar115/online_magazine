<?php
require_once 'db_connect.php';
require_once 'user.php';
/**
 * The User Mapper provides a mapping between User objects and the database. 
 */
class UserMapper {
	private $_connection;
	function __construct($connection) {
		$this->_connection = $connection;
	}
	/**
	 * Insert a new user to the database. 
	 * 
	 * @param unknown $username the username
	 * @param unknown $password the password.
	 */
	function createNewUser($username, $password) {
		$this->_connection->_buildInsertQuery ( array (
				"name" => $username,
				"password" => $password 
		), "users" );
	}
	/**
	 * Get the User object representation of an user. 
	 * 
	 * @param unknown $username the username
	 * @param unknown $password the password
	 * @return User|boolean the user object, false if failure to create it
	 */
	function getUserByAcc($username, $password) {
		$response = $this->_connection->selectAllFromWhere ( "users", "`name`=:name and `password`=:password", array (
				":name" => $username,
				":password" => $password 
		) );
		$result = $response->fetch ();
		try {
			return new User ( $result );
		} catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Check if a user's credentials are valid. 
	 * 
	 * @param unknown $username the username
	 * @param unknown $password the password
	 * @return boolean true if valid i.e. found in the database, false otherwise
	 */
	function validUserCredentials($username, $password) {
		$response = $this->_connection->selectAllFromWhere ( "users", "`name`=:name and `password`=:password", array (
				":name" => $username,
				":password" => $password 
		) );
		return ! $this->_connection->responseIsEmpty ( $response );
	}
	/**
	 * Get the user representation of an user using his username
	 * 
	 * @param unknown $username the username
	 * @return User|boolean the user object
	 */
	function getUserByName($username) {
		$response = $this->_connection->selectAllFromWhere ( "users", "`name`=:name", array (
				":name" => $username 
		) );
		$result = $response->fetch ();
		try {
			return new User ( $result );
		} catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Check if a username is already taken. 
	 * 
	 * @param unknown $username the username
	 * @return boolean true if taken, false otherwise
	 */
	function usernameTaken($username) {
		$response = $this->_connection->selectAllFromWhere ( "users", "`name`=:name", array (
				":name" => $username 
		) );
		return ! $this->_connection->responseIsEmpty ( $response );
	}
	/**
	 * Get user object by id. 
	 * 
	 * @param unknown $id the id of the user. 
	 * @return User|boolean user object 
	 */
	function getUserById($id) {
		$response = $this->_connection->selectAllFromWhere ( "users", "`id`=:id", array (
				":id" => $id 
		) );
		$result = $response->fetch ();
		try {
			return new User ( $result );
		} catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Check if a user has previously liked an article. 
	 * 
	 * @param unknown $article_id the id of the article
	 * @param unknown $user_id the id of the user
	 * @return boolean|string false if no, type of like if yes (can be dislike or like)
	 */
	function userHasLiked($article_id, $user_id) {
		$response = $this->_connection->selectAllFromWhere ( "likes", "`article_id`=:article_id and `user_id`=:user_id", array (
				":article_id" => $article_id,
				":user_id" => $user_id 
		) );
		if ($this->_connection->responseIsEmpty ( $response )) {
			return false;
		} else {
			$row = $response->fetch ();
			if ($row ['is_like'] == '0') {
				return "dislike";
			} elseif ($row ['is_like'] == '1') {
				return 'like';
			}
		}
	}
	/**
	 * Get all the users who are writers. 
	 * 
	 * @return multitype: array of writers. 
	 */
	function getAllWriters() {
		$result = $this->_connection->selectAllFrom ( "writers_list_view" );
		$output = [ ];
		while ( $row = $result->fetch () ) {
			array_push ( $output, new User ( $row ) );
		}
		return $output;
	}
	/**
	 * Get all the users who are writers, excluding the logged in user if he's a writer.
	 * 
	 * @return Ambigous <multitype:, multitype:> array list of writers. 
	 */
	function getAllOtherWriters() {
		$writers = $this->getAllWriters ();
		foreach ( $writers as $key => $writer ) {
			if ($writer->getName () == $_SESSION ['Username']) {
				unset ( $writers [$key] );
				return $writers;
			}
		}
		return $writers;
	}
	/**
	 * Get all users who are not publishers. 
	 * 
	 * @return multitype: array of users who are not publishers
	 */
	function getAllNonPublisherUsers() {
		$result = $this->_connection->selectAllFrom ( "non_publisher_users_view" );
		$output = [ ];
		while ( $row = $result->fetch () ) {
			array_push ( $output, new User ( $row ) );
		}
		return $output;
	}
	/**
	 * Promote a user. 
	 * 
	 * @param unknown $user_id the id of the user
	 * @param unknown $new_type the new type of the user
	 */
	function promoteUserType($user_id, $new_type) {
		$sql = $this->_connection->_buildUpdateQuery ( array (
				"type" => $new_type 
		), "users", array (
				"id" => $user_id 
		) );
	}
	/**
	 * Get the users who have written a set of comments. 
	 * 
	 * @param unknown $Comments array of comments 
	 * @return multitype:NULL array of users associated with the comments
	 */
	function getAuthorsOfComments($Comments) {
		$Users = array ();
		foreach ( $Comments as $key => $value ) {
			$Users [$key] = $this->getUserById ( $value->getUserId () );
		}
		return $Users;
	}
}