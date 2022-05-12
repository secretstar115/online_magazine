<?php
class View {
	// An array of variables. 
	protected $_variables = array ();
	// The template
	protected $_template;
	
	/**
	 * Add a variable to the list of variables
	 * 
	 * @param unknown $name the name of the variable
	 * @param unknown $value the contents of the variable
	 */
	public function set($name, $value) {
		$this->_variables [$name] = $value;
	}
	/**
	 * Draw the view. 
	 */
	public function render() {
		// Extract all the variables, making them available 
		extract ( $this->_variables );
		include 'templates/header.php';
		include ($this->template);
		include 'templates/footer.php';
	}
	/**
	 * Set the template
	 * 
	 * @param unknown $template the file path to the template
	 */
	function setTemplate($template) {
		$this->template = $template;
	}
}