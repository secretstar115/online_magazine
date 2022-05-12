<?php
require_once 'models/model.php';
require_once 'mappers/user_mapper.php';
require_once 'mappers/article_mapper.php';
require_once 'models/mappers/db_connect.php';
/**
 * This is the model of the member area.
 * It contains functions for changing the users of the magazine.
 * It also contains functions for populating new content to the magazine.
 */
class MemberModel extends Model {
	function __construct($view) {
		parent::__construct ( $view );
		$connection = new DBConnect ();
		$this->userMapper = new UserMapper ( $connection );
		$this->articleMapper = new ArticleMapper ( $connection );
		// Prepare different data for the member page depending on the user type
		if (isWriter ()) {
			$user = $this->userMapper->getUserByName ( $_SESSION ['Username'] );
			$this->set ( "UserArticles", $this->articleMapper->getUserArticles ( $user ) );
			$this->set ( "WriterList", $this->userMapper->getAllOtherWriters () );
		}
		if (isEditor ()) {
			$submitted_articles = $this->articleMapper->fetchAllSubmittedArticles ();
			$this->set ( "SubmittedArticles", $submitted_articles );
			$this->set ( "EditHistory", $this->articleMapper->getEditHistoryOfUser ( $_SESSION ["UserId"] ) );
			$this->set ( "FeaturedArticles", $this->articleMapper->fetchFeaturedArticles () );
		}
		if (isPublisher ()) {
			$non_publishers = $this->userMapper->getAllNonPublisherUsers ();
			$this->set ( "NonPublishers", $non_publishers );
		}
	}
	/**
	 * Verify the account details of an user. 
	 * 
	 * @param unknown $username the username
	 * @param unknown $password the password
	 */
	function verifyAccountDetails($username, $password) {
		try {
			if ($this->userMapper->validUserCredentials ( $username, $password )) {
				$user = $this->userMapper->getUserByAcc ( $username, $password );
				$this->set ( "UserExists", true );
				$this->set ( "UserType", $user->getType () );
				$_SESSION ["UserId"] = $user->getId ();
				$_SESSION ['Username'] = $username;
				$_SESSION ['LoggedIn'] = 1;
				$_SESSION ['UserType'] = $user->getType ();
				addMessage ( "success", "You are now logged in." );
			} else {
				$this->set ( "UserExists", false );
				addMessage ( "error", "Error logging in. Please check your account details." );
			}
		} catch ( DBException $e ) {
			addMessage ( "error", "Error logging in." );
		}
	}
	/**
	 * Create a new user account for a user. 
	 * 
	 * @param unknown $username the new username
	 * @param unknown $password the password associated with the username
	 */
	function registerNewUser($username, $password) {
		try {
			if (! $this->userMapper->usernameTaken ( $username )) {
				$this->userMapper->createNewUser ( $username, $password );
				$this->set ( "CreatedUser", true );
				$this->set ( "username", $username );
				addMessage ( "success", "Successfully created user " . $username . ". You can now login." );
			} else {
				$this->set ( "CreatedUser", false );
				addMessage ( "error", "Username already taken. Please try again." );
			}
		} catch ( DBException $e ) {
			addMessage ( "error", "Cannot create user." );
		}
	}
	/**
	 * Submit a new article to the magazine. 
	 * 
	 * @param unknown $data the data of the article. 
	 */
	function submitArticle($data) {
		try {
			$this->articleMapper->submitNewArticle ( $data );
			addMessage ( "success", "Successfully added article" . $data ["title"] . "!" );
		} catch ( DBException $e ) {
			addMessage ( "error", "Error! Failed to add " . $data ["title"] . "! Contact the site owner." );
		}
	}
	/**
	 * Submit a new review. 
	 * 
	 * @param unknown $data
	 */
	function submitReview($data) {
		try {
			$this->articleMapper->submitReview ( $data );
			addMessage ( "success", "Successfully added review " . $data ["title"] . "!" );
		} catch ( DBException $e ) {
			addMessage ( "error", "Error! Failed to add " . $data ["title"] . "! Contact the site owner." );
		}
	}
	/**
	 * Submit a column article. 
	 * 
	 * @param unknown $data
	 */
	function submitColumnArticle($data) {
		try {
			$this->articleMapper->submitColumnArticle ( $data );
			addMessage ( "success", "Successfully added column article " . $data ["title"] . "!" );
		} catch ( DBException $e ) {
			addMessage ( "error", "Error! Failed to add " . $data ["title"] . "! Contact the site owner." );
		}
	}
	/**
	 * Update an article. 
	 * 
	 * @param unknown $data
	 */
	function updateArticle($data) {
		$article_id = $data ["id"];
		unset ( $data ["id"] );
		try {
			$this->articleMapper->updateArticle ( $data, $article_id );
			addMessage ( "success", "Successfully updated article" . $data ["title"] . "!" );
		} catch ( ArticleMapperException $e ) {
			addMessage ( "error", "Error! Failed to update " . $data ["title"] . "! Contact the site owner." );
		}
	}
	/**
	 * Update a review. 
	 * 
	 * @param unknown $data
	 */
	function updateReview($data) {
		$article_id = $data ["id"];
		unset ( $data ["id"] );
		try {
			$this->articleMapper->updateReview ( $data, $article_id );
			addMessage ( "success", "Successfully updated review" . $data ["title"] . "!" );
		} catch ( DBException $e ) {
			addMessage ( "error", "Error! Failed to update review " . $data ["title"] . "! Contact the site owner." );
		}
	}
	/**
	 * Update a column article. 
	 * 
	 * @param unknown $data
	 */
	function updateColumnArticle($data) {
		$article_id = $data ["id"];
		unset ( $data ["id"] );
		try {
			$this->articleMapper->updateColumnArticle ( $data, $article_id );
			addMessage ( "success", "Successfully updated column article " . $data ["title"] . "!" );
		} catch ( DBException $e ) {
			addMessage ( "error", "Error! Failed to update column article " . $data ["title"] . "! Contact the site owner." );
		}
	}
	/**
	 * Promote a user. 
	 * 
	 * @param unknown $user_id the id of the user
	 * @param unknown $new_type the new type of the user
	 */
	function promoteUser($user_id, $new_type) {
		try {
			$this->userMapper->promoteUserType ( $user, $new_type );
			addMessage ( "success", "Successfully promoted user" . $user . " to " . $new_type . "." );
		} catch ( DBException $e ) {
			addMessage ( "error", "Error! Failed to promote user." );
		}
	}
}