<?php
/**
 * This is the base class used in mappings. 
 */
class ObjectMap {
	/**
	 * Construct an object using an array of key=>valued pairs, automatically
	 * populating all the class variables through key comparison.
	 *
	 * This is passed on to children. It allows us to construct objects by directly
	 * passing sql rows.
	 *
	 * @param unknown $row an array of key=>values
	 * @throws Exception if no key matching a class variable is found in the array
	 */
	public function __construct($row) {
		if (!is_array($row)) {
			throw new Exception ( "No rows returned from query or wrong model." );
		}
		//var_dump($row);
		// Get the class variables for the current class.
		$class_vars = get_class_vars ( get_class ( $this ) );
		// var_dump($class_vars);
		// Compare every key from the array to the variables.
		foreach ( array_keys ( $class_vars ) as $value ) {
			if (array_key_exists ( $value, $row )) {
				$this->$value = fixEncoding ( $row [$value] );
			} else {
				throw new Exception ( "No row " . $value . " returned from query or wrong model." );
			}
		}
	}
	/**
	 * Return an array representation of an object. 
	 * 
	 * @return multitype:NULL
	 */
	public function serialize() {
		$output = array ();
		$class_vars = get_class_vars ( get_class ( $this ) );
		foreach ( array_keys ( $class_vars ) as $name ) {
			if ($this->$name != null) {
				$output [$name] = $this->$name;
			}
		}
		return $output;
	}
}