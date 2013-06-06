<?php
class ModelAction extends Model implements Persistable {
	private $id;
	private $user_id;
	private $user;
	private $when;
	private $type;
	private $object;
	private $value;
	
	public function getDescription() {
		$s = '';
		switch($this->getType()) {
			case 'create':
				$s .= '<b>création</b>'; break;
			case 'update':
				$s .= '<b>modification</b>'; break;
			case 'delete':
				$s .= '<b>suppression</b>'; break;
		}
		$s .= " de <em>{$this->getObject()}</em>";
		if($this->getValue())
			$s .= ' : '.$this->getValue();
		return $s;
	}

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

	public function getUser() {
		if($this->user == null)
			$this->user = CollectionUser::newInstance()->find($this->getUser_id());
		return $this->user;
	}

	public function setUser(ModelUser $user) {
		$this->user = $user;
		return $this;
	}

	public function getWhen() {
		return $this->when;
	}

	public function setWhen($when = null) {
		if($when === null)
			$when = date("Y-m-d H:i:s");
		$this->when = $when;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function getObject() {
		return $this->object;
	}

	public function setObject($object) {
		$this->object = $object;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	static public function getPersistentId() {
		return array('id');
	}
	
	public function getPersistentData() {
		return $this->getPersistentDataNatural(array('user_id', 'when', 'type', 'object', 'value'));
	}
}