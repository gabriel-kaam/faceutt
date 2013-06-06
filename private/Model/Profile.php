<?php
class ModelProfile extends Model implements Persistable {
	private $user_id;
	private $parameters;
	
	public function __construct() {
		$this->parameters = array(
				'last_name'		=> null
				,'first_name'	=> null
				,'sex'			=> null
				,'prog'			=> null
				,'semester'		=> null
				,'photos'		=> array()
				,'skills'		=> array());
	}

	public function getUser_id() {
		return $this->user_id;
	}

	public function setUser_id($user_id) {
		$this->user_id = $user_id;
		return $this;
	}

	public function getParameters() {
		return $this->parameters;
	}

	public function getParameter($name) {
		return isset($this->parameters[$name]) ? $this->parameters[$name] : false;
	}

	public function addParameter(ModelParameter $parameter) {
		$this->parameters[$parameter->getName()] = $parameter;
		return $this;
	}

	public function delParameter(ModelParameter $parameter) {
		unset($this->parameters[$parameter->getName()]);
		return $this;
	}

	public function hydrate(Array $data) {
		foreach($this->getParameters() as $k => $v)
			if(array_key_exists($k, $data)
					&& array_key_exists($k.'V', $data)
					&& array_key_exists($k.'T', $data)) {
				$a = new ModelParameter();
				$this->addParameter($a->hydrate(array(
						'name'			=> $k,
						'value'			=> $data[$k],
						'type'			=> $data[$k.'T'],
						'visibility'	=> $data[$k.'V'])));
			}

		$b = new ModelParameter();
		$c = new ModelParameter();

		$this->addParameter($b->hydrate(array(
				'name'			=> 'photos',
				'value'			=> null,
				'visibility'	=> isset($data['photosV']) ? $data['photosV'] : 'prive')));
		$this->addParameter($c->hydrate(array(
				'name'			=> 'skills',
				'value'			=> null,
				'visibility'	=> isset($data['skillsV']) ? $data['skillsV'] : 'prive')));

		return parent::hydrate($data);
	}

	static public function getPersistentId() {
		return array('user_id');
	}

	public function getPersistentData() {
		return array(
				'last_name'		=> $this->getParameter('last_name')		? $this->getParameter('last_name')->getValue()	: null
				,'first_name'	=> $this->getParameter('first_name')	? $this->getParameter('first_name')->getValue()	: null
				,'sex'			=> $this->getParameter('sex')			? $this->getParameter('sex')->getValue()		: null
				,'semester'		=> $this->getParameter('semester')		? $this->getParameter('semester')->getValue()	: null
				,'prog'			=> $this->getParameter('prog')			? $this->getParameter('prog')->getValue()		: null

				,'last_nameV'	=> $this->getParameter('last_name')		? $this->getParameter('last_name')->getVisibility()		: null
				,'first_nameV'	=> $this->getParameter('first_name')	? $this->getParameter('first_name')->getVisibility()	: null
				,'sexV'			=> $this->getParameter('sex')			? $this->getParameter('sex')->getVisibility()			: null
				,'semesterV'	=> $this->getParameter('semester')		? $this->getParameter('semester')->getVisibility()		: null
				,'progV'		=> $this->getParameter('prog')			? $this->getParameter('prog')->getVisibility()			: null
				,'photosV'		=> $this->getParameter('photos')		? $this->getParameter('photos')->getVisibility()		: null
				,'skillsV'		=> $this->getParameter('skills')		? $this->getParameter('skills')->getVisibility()		: null
		);
	}
}