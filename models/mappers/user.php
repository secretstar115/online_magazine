<?php
require_once 'object_map.php';

/**
 * Represents an User.
 * There are 4 types of users, namely subscribers, writers, editors and publishers.
 */
class User extends ObjectMap {
	protected $id;
	protected $name;
	protected $password;
	protected $type;
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getPassword() {
		return $this->password;
	}
	public function getType() {
		return $this->type;
	}
}