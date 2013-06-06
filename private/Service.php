<?php
abstract class Service extends Objectx {
	static protected $instance = array();

	protected function __construct() {
	}

	static public function getInstance() {
		if(!isset(static::$instance[get_called_class()]))
			static::$instance[get_called_class()] = new static();
		return static::$instance[get_called_class()];
	}
}
