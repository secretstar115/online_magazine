<?php
/* Contains some useful methods for the magazine website */

/**
 * Check if there is a user logged in.
 *
 * @return boolean true if yes
 */
function loggedIn() {
	return (! empty ( $_SESSION ['LoggedIn'] ) && ! empty ( $_SESSION ['Username'] ));
}
/**
 * Check if there is a current user who's a subscriber.
 *
 *
 * @return boolean true if subscriber
 */
function isSubscriber() {
	return loggedIn () && in_array ( $_SESSION ['UserType'], unserialize ( SUBSCRIBERS ) );
}
/**
 * Check if there is a current user who's a writer.
 *
 *
 * @return boolean true if writer
 */
function isWriter() {
	return loggedIn () && in_array ( $_SESSION ['UserType'], unserialize ( WRITERS ) );
}
/**
 * Check if there is a current user who's a editor.
 *
 * @return boolean true if editor
 */
function isEditor() {
	return loggedIn () && in_array ( $_SESSION ['UserType'], unserialize ( EDITORS ) );
}
/**
 * Check if there is a current user who's a publisher.
 *
 * @return boolean true if publisher
 */
function isPublisher() {
	return loggedIn () && $_SESSION ['UserType'] == PUBLISHER;
}

/**
 * Check if a column name matches our column list.
 *
 *
 * @param unknown $column
 *        	the name of the column
 * @return boolean true if there's a match.
 */
function isValidColumn($column) {
	return in_array ( $column, unserialize ( COLUMNS ) );
}
/**
 * Get username of currently logged user
 * 
 * @return unknown|boolean
 */
function getCurrUsername() {
	if (isset ( $_SESSION ['Username'] )) {
		return $_SESSION ['Username'];
	} else {
		return false;
	}
}
/**
 * Get id of currently logged user
 * 
 * @return unknown|boolean
 */
function getCurrUserId() {
	if (isset ( $_SESSION ['UserId'] )) {
		return $_SESSION ['UserId'];
	} else {
		return false;
	}
}

/**
 * Fix an encoding of a string to UTF-8 and remove some unnecessary characters.
 *
 *
 * @param unknown $str        	
 * @return string
 */
function fixEncoding($str) {
	$junk = array (
			"’" => "'",
			"“" => "\"",
			"”" => "\"" 
	);
	foreach ( $junk as $key => $value ) {
		$str = str_replace ( $key, $value, $str );
	}
	return mb_convert_encoding ( $str, 'UTF-8', 'UTF-8' );
}
/**
 * Display any messages in $_SESSION ['error_messages'] by pushing
 * some javascript to the current page.
 *
 *
 * @param unknown $ignore_request
 *        	this is an array of actions that should be ignored.
 *        	Basically if the request is coming from one of those we will
 *        	delay printing of the message, since we may like to redirect
 *        	to another webpage and display it there instead.
 */
function displayMessage($ignore_request = array()) {
	if (empty ( $_SESSION ['error_messages'] ) || ! isset ( $_SESSION ['error_messages'] )) {
		return;
	} else {
		if (! empty ( $ignore_request )) {
			$expl = explode ( "/", $_SERVER ['REQUEST_URI'] );
			$ind = count ( $expl ) - 2;
			foreach ( $ignore_request as $action ) {
				if (endsWith ( $_SERVER ['REQUEST_URI'], $action ) || $expl [$ind] == $action) {
					return;
				}
			}
		}
		foreach ( $_SESSION ['error_messages'] as $key => $value ) {
			echo '<script type="text/javascript">', 'alert_' . $key . '_message("' . $value . '");', '</script>';
		}
		$_SESSION ['error_messages'] = array ();
	}
}
/**
 * Add a message to $_SESSION ['error_messages'],
 * to be displayed to the user.
 *
 *
 * @param unknown $type
 *        	the type of message, can be success or error
 * @param unknown $message
 *        	the message text
 */
function addMessage($type, $message) {
	if (! isset ( $_SESSION ['error_messages'] )) {
		$_SESSION ['error_messages'] = array ();
	}
	$_SESSION ['error_messages'] [$type] = $message;
}

/**
 * CHeck if a string ends with another string.
 *
 *
 * @param unknown $target_str
 *        	the search to be matched upon
 * @param unknown $search
 *        	the suffix we're looking for
 * @return boolean true if there's a match
 */
function endsWith($target_str, $search) {
	return substr ( $target_str, - strlen ( $search ) ) == $search;
}
