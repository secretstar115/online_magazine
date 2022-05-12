<?php
/* This is the root file of the application */

// Configuration file
require_once 'config.php';
require_once 'util/util.php';
session_start ();

/**
An array of paths => controllers. 
 */
$controllers = array (
		'Article' => 'ArticleController',
		'Home' => 'HomeController',
		'Member' => 'MemberController' , 
		'Write' => 'WriteController', 
		'Latest' => 'LatestController'
);
/**
 * The main method of the website, it redirects all calls 
 * to the correct location. 
 */
function redirect() {
	global $controllers;
	
	if (empty ( $_GET )) {
		includeRequired ( "home" );
		$controller = new HomeController ( "HomeView", "HomeModel" );
	} else {
		$arg = $_GET ["controller"];
		$controller_class = null;
		foreach ( $controllers as $component => $class ) {
			if (strcasecmp ( $arg, $component ) == 0) {
				includeRequired ( $component );
				$arguments = getArguments ();
				$controller_class = createComponents ( $arguments, $component, $class );
				break;
			}
		}
		if ($controller_class == null) {
			/* echo $arg; */
			require_once 'control/pagenotfound.php';
		}
	}
}
/**
 * Add required files for a component. 
 * 
 * @param string $component
 */
function includeRequired($component) {
	require_once 'control/' . $component . '_controller.php';
	require_once 'models/' . $component . '_model.php';
	require_once 'view/' . $component . '_view.php';
}
/**
 * Try to create a controller and call an action if available. 
 * Return null if failed. 
 * 
 * @param array $arguments extra parameters to be passed to the controller
 * @param string $component the name of the component
 * @param string $class the class name of the controller
 * @return NULL|string
 */
function createComponents($arguments, $component, $class) {
	if ($arguments == null) {
		return null;
	}
	$controller = new $class ( $component . "View", $component . "Model" );
	if ($arguments == "no_args") {
		return $class;
	} else {
		if (method_exists ( $class, $arguments [0] )) {
			if (sizeof ( $arguments ) == 1) {
				call_user_func_array ( array (
						$controller,
						$arguments [0] 
				), array () );
			} else {
				call_user_func_array ( array (
						$controller,
						$arguments [0] 
				), array (
						$arguments [1] 
				) );
			}
			return $class;
		} else {
			return null;
		}
	}
}
/**
 * 
 * @return NULL|multitype:
 */
function getArguments() {
	$arguments = $_GET ["arguments"];
	if(empty($arguments)) {
		return "no_args";
	}
	$arguments = ltrim ( $arguments, '/' );
	$extra = explode ( "/", $arguments );
	if (count ( $extra ) > 2) {
		return null;
	}
	return $extra;
}

redirect ();

