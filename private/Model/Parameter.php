<?php
class ModelParameterType {
	private $raw;
	private $type;
	private $args;

	public function __construct($raw) {
		$this->setRaw($raw);
	}

	public function getRaw() {
		return $this->raw;
	}

	public function setRaw($raw) {
		$this->raw = $raw;
		list($a, $b) = explode('(', $raw, 2);
		$this->setType($a);
		$this->setArgs(rtrim($b, ')'));
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	private function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function getArgs() {
		return $this->args;
	}

	private function setArgs($args) {
		$this->args = $args;
		return $this;
	}

	public function allows($value) {
		if($value == '')
			return true; // FIXME , right thing to do?

		switch($this->getType()) {
			case 'int':
			case 'tinyint':
			case 'bigint':
				return ctype_digit($value);
				
			case 'varchar':
			case 'text':
				return is_string($value); // may be useless?

			case 'enum':
				return in_array($value, array_map(function($a) {return trim($a, "'");}, explode(',', $this->getArgs())));
				
			default:
				return true; // hmm?
		}
	}
}

class ModelParameter extends Model {
	private $name;
	private $value;
	private $type;
	private $visibility;

	static public $validVisibilities	= array('public', 'amis' , 'prive');

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getValue($nice = false) {
		if(empty($this->value) && $nice)
			return 'N/A';
		return $this->value;
	}

	public function setValue($value) {
		if($this->getType() // be careful, some parameters dont have types (photos, skills)
				&& !$this->getType()->allows($value))
			return false;
		$this->value = $value;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = new ModelParameterType($type);
		return $this;
	}

	public function getVisibility() {
		if($this->visibility == null)
			return 'prive'; // not persistent
		return $this->visibility;
	}

	public function setVisibility($visibility) {
		if(self::isValidVisibility($visibility))
			$this->visibility = $visibility;
		return $this;
	}
	
	static public function isValidVisibility($visibility) {
		return in_array($visibility, self::$validVisibilities);
	} 
}