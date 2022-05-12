<?php
/* This is the config file of the application */

error_reporting ( E_ALL );
ini_set ( 'display_errors', 'On' );
mb_language ( 'uni' );
mb_internal_encoding ( 'UTF-8' );
date_default_timezone_set("Europe/London");
define ( "DB_DSN", "mysql:host=127.0.0.1;dbname=cs_news" );
define ( "DB_USERNAME", "root" );
define ( "DB_PASSWORD", "" );

define ( "HOMEPAGE_NUM_ARTICLES", 10 );
$app_URI = rtrim ( $_SERVER ['REQUEST_URI'], '/' );

$expl = explode ( "\\", dirname ( __FILE__ ) );
define ( 'ROOT', "/" . end ( $expl ) );
define ( "CSS_PATH", ROOT . "/css" );
define ( "IMAGE_PATH", ROOT . "/images" );
define ( "JS_PATH", ROOT . "/js" );

define ( "SUBSCRIBER", "subscriber" );
define ( "WRITER", "writer" );
define ( "EDITOR", "editor" );
define ( "PUBLISHER", "publisher" );

define ( "SUBSCRIBERS", serialize ( array (
		SUBSCRIBER,
		WRITER,
		EDITOR,
		PUBLISHER 
) ) );
define ( "WRITERS", serialize ( array (
		WRITER,
		EDITOR,
		PUBLISHER 
) ) );
define ( "EDITORS", serialize ( array (
		EDITOR,
		PUBLISHER 
) ) );

$keywords = array (
		"tech" => "Technology",
		"department" => "Department related" 
);

define ( "COLUMNS", serialize ( array (
		"tech",
		"cs_success" 
) ) );
define ( "KEYWORDS", serialize ( $keywords ) );

$_SESSION['error_messages'] = array();
