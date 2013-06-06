<?php
class ModelSkill extends ModelPhoto {
	private $name;

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getPersistentData() {
		return $this->getPersistentDataNatural(array('user_id', 'name'));
	}
}
