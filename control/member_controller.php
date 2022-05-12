<?php
require_once 'control/controller.php';
/**
 * The Member Controller handles most user related interaction, including
 * adding/changing content, login, etc.
 */
class MemberController extends Controller {
	/**
	 * Check if login details coming from POST work.
	 */
	function login_check() {
		if (! empty ( $_POST ['username'] ) && ! empty ( $_POST ['password'] )) {
			$this->_model->verifyAccountDetails ( $_POST ['username'], $_POST ['password'] );
		}
		header ( 'Location: ' . $_SERVER ['HTTP_REFERER'] );
	}
	/**
	 * Load the register template
	 */
	function register() {
		$this->setTemplate ( "templates/register.php" );
	}
	/**
	 * Load the user guide
	 */
	function guide() {
		$this->setTemplate ( "templates/user-guide.php" );
	}
	/**
	 * Create a new user of the magazine - Subscriber by default.
	 *
	 * Account details are optained through post.
	 */
	function create_user() {
		if (! loggedIn ()) {
			if (! empty ( $_POST ['username'] ) && ! empty ( $_POST ['password'] )) {
				$username = $_POST ['username'];
				$password = $_POST ['password'];
				$this->_model->registerNewUser ( $username, $password );
			}
			header ( 'Location: ' . $_SERVER ['HTTP_REFERER'] );
		}
	}
	/**
	 * Submit new article data, written by a writer.
	 */
	function submit() {
		$data = $_POST ['article'];
		if (array_key_exists ( "writer", $data )) {
			array_push ( $data ["writer"], $_SESSION ['UserId'] );
		} else {
			$data ["writer"] = array (
					$_SESSION ['UserId'] 
			);
		}
		$this->_articleDataRequest ( $data, "submit" );
		header ( 'Location: ' . $_SERVER ['HTTP_REFERER'] );
	}
	/**
	 * Submit article edit data.
	 *
	 *
	 * @param unknown $article_id
	 *        	the id of the article that's being edited
	 */
	function edit($article_id) {
		$data = $_POST ['article'];
		$data ["id"] = $article_id;
		if (array_key_exists ( "writer", $data )) {
			if (! in_array ( $_SESSION ['UserId'], $data ["writer"] ) && $_SESSION ['UserType'] == "writer") {
				array_push ( $data ["writer"], $_SESSION ['UserId'] );
			}
		}
		$this->_articleDataRequest ( $data, "update" );
		header ( 'Location: ' . $_SERVER ['HTTP_REFERER'] );
	}
	/**
	 * This is an internal method which unites the common functionality of submit and edit.
	 *
	 * It uploads an image to the image folder...
	 *
	 * @param unknown $data        	
	 * @param unknown $type        	
	 */
	private function _articleDataRequest($data, $type) {
		$image_path = $this->_uploadImage ();
		if ($image_path) {
			$data ["image_path"] = $image_path;
		}
		if ($type == "update") {
			if (array_key_exists ( "date", $data )) {
				unset ( $data ["date"] );
			}
		} else {
			$data ["date"] = date ( 'Y-m-d H:i:s' );
			$data ["body"] = nl2br ( htmlentities ( $data ["body"], ENT_QUOTES, 'UTF-8' ) );
		}
		// var_dump ( $data );
		switch ($data ["type"]) {
			case "article" :
				// echo "article";
				unset ( $data ["rating"] );
				unset ( $data ["column_article"] );
				$action = $type . "Article";
				$this->_model->$action ( $data );
				break;
			case "column_article" :
				// echo "column_article";
				unset ( $data ["rating"] );
				$action = $type . "ColumnArticle";
				$this->_model->$action ( $data );
				break;
			case "review" :
				// echo "review";
				unset ( $data ["column_article"] );
				$action = $type . "Review";
				$this->_model->$action ( $data );
				break;
		}
	}
	function promote_user() {
		if (! isPublisher ()) {
			header ( 'Location: ' . ROOT );
		}
		$user_id = $_POST ["user"];
		$new_type = $_POST ["newtype"];
		$this->_model->promoteUser ( $user_id, $new_type );
		header ( 'Location: ' . $_SERVER ['HTTP_REFERER'] );
	}
	/*
	 * function login() { $this->setTemplate ( "templates/login.php" ); }
	 */
	function logout() {
		if (loggedIn ()) {
			$_SESSION = array ();
			session_destroy ();
			addMessage ( "success", "You've logged out." );
			header ( 'Location: ' . $_SERVER ['HTTP_REFERER'] );
		}
	}
	/**
	 * Store an image in the article_img folder on the server.
	 *
	 * @return NULL string path to the image, to be stored in the database for future reference, null if error
	 */
	private function _uploadImage() {
		$abs_path = "";
		if ($_FILES ["file"] ["error"] > 0) {
			return null;
		} else {
			$abs_path = $abs_path . getcwd () . "\\article_img\\" . $_FILES ["file"] ["name"];
			if (file_exists ( $abs_path )) {
				// echo $_FILES ["file"] ["name"] . " already exists. ";
			} else {
				move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $abs_path );
			}
		}
		$rel_path = "/article_img/" . $_FILES ["file"] ["name"];
		return $rel_path;
	}
}
