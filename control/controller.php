<?php
require_once 'view/view.php';
require_once 'models/model.php';

/**
 * This is the base of our Controller. 
 *
 */
class Controller {
	// The model 
	protected $_model;
	function __construct($view, $model) {
		$this->_model = new $model($view);		
	}

	/**
	 * Set the current template. 
	 * 
	 * @param String $template the file path to the template
	 */
	function setTemplate($template) {
		$this->_model->setTemplate($template);
	}

}