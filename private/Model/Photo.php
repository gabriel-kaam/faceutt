<?php
class ModelPhoto extends Model implements Persistable {
	private $id;
	private $user_id;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function getUser_id() {
		return $this->user_id;
	}
	
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
		return $this;
	}
	
	static public function getPersistentId() {
		return array('id');
	}

	public function getPersistentData() {
		return $this->getPersistentDataNatural(array('user_id'));
	}
}
