<?php
class ModelUser_has_user extends Model implements Persistable {
	private $id;
	private $user_id1;
	private $user_id2;
	private $user1;
	private $user2;
	private $type;

	const KNOW = 0x01;
	const WORK = 0x02;
	const FRIE = 0x04;
	const LIKE = 0x08;

	static public $shortNames = array(
			self::KNOW => 'know',
			self::WORK => 'work',
			self::FRIE => 'friend',
			self::LIKE => 'like',
	);

	static public $icoNames = array(
			'work'		=> 'briefcase',
			'friend' 	=> 'thumbs-up',
			'know'		=> 'user',
			'like'		=> 'heart',
	);

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getUser_id1() {
		return $this->user_id1;
	}

	public function setUser_id1($user_id1) {
		$this->user_id1 = $user_id1;
		return $this;
	}

	public function getUser_id2() {
		return $this->user_id2;
	}

	public function setUser_id2($user_id2) {
		$this->user_id2 = $user_id2;
		return $this;
	}
	
	public function getUser1() {
		if($this->user1 == null)
			$this->user1 = CollectionUser::newInstance()->find($this->getUser_id1());
		return $this->user1;
	}

	public function setUser1($user1) {
		$this->user1 = $user1;
		return $this;
	}
	
	public function getUser2() {
		if($this->user2 == null)
			$this->user2 = CollectionUser::newInstance()->find($this->getUser_id2());
		return $this->user2;
	}
	
	public function setUser2($user2) {
		$this->user2 = $user2;
		return $this;
	}

	public function getType() {
		return intval($this->type);
	}

	public function setType($type) {
		$this->type = intval($type);
		return $this;
	}

	public function enableType($type) {
		$this->setType($this->getType() | intval($type));
		return $this;
	}

	public function disableType($type) {
		$this->setType($this->getType() & ~intval($type));
		return $this;
	}

	public function checkType($mask) {
		return $this->getType() & $mask;
	}

	static public function getPersistentId() {
		return array('user_id1', 'user_id2');
	}

	public function getPersistentData() {
		return $this->getPersistentDataNatural(array('type'));
	}
}
