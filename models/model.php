<?php

/**
 * This is the base of our Model. 
 *
 */
class Model {
	// The view
	private $_view;
	function __construct($view) {
		$this->_view = new $view ();
	}
	
	/**
	 * Pass a variable to the view.
	 * All the variables passed will be available for the view to use.
	 *
	 * @param unknown $name the name of the variable
	 * @param unknown $value the value
	 */
	function set($name, $value) {
		$this->_view->set ( $name, $value );
	}
	/**
	 * Render the view on destruct.
	 */
	function __destruct() {
		$this->_view->render ();
	}
	/**
	 * Set the template in the view.
	 * 
	 * @param String $template the file path to the template
	 */
	function setTemplate($template) {
		$this->_view->setTemplate ( $template );
	}
}