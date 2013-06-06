<?php
class ModelUser extends Model implements Persistable {
	private $id;
	private $login;
	private $hash;
	private $inBounds;
	private $outBounds;
	private $reputation;
	private $skills;
	private $photos;
	private $profile;
	private $user_has_user;

	public function __construct() {
		$this->skills			= array();
		$this->photos			= array();
		$this->user_has_user	= array();
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getLogin() {
		return $this->login;
	}

	public function setLogin($login) {
		$this->login = $login;
		return $this;
	}

	public function getHash() {
		return $this->hash;
	}

	public function setHash($hash) {
		$this->hash = $hash;
		return $this;
	}

	public function getInBounds() {
		return $this->inBounds;
	}

	public function setInBounds($inBounds) {
		$this->inBounds = $inBounds;
		return $this;
	}

	public function getOutBounds() {
		return $this->outBounds;
	}

	public function setOutBounds($outBounds) {
		$this->outBounds = $outBounds;
		return $this;
	}

	public function getReputation() {
		if($this->reputation == null)
			$this->setReputation($this->getInBounds() / ($this->getInBounds() + $this->getOutBounds()));
		return $this->reputation;
	}

	public function setReputation($reputation) {
		$this->reputation = $reputation;
		return $this;
	}

	public function getUser_has_user(ModelUser $user) {
		if(!isset($this->getUser_has_users()[$user->getId()])) // FIXME : good idea?
			return ModelUser_has_user::newInstance()->hydrate(array(
					'user_id1'	=> $this->getId(),
					'user_id2'	=> $user->getId(),
					'type'		=> 0));
		return $this->user_has_user[$user->getId()];
	}

	public function getUser_has_users() {
		if(empty($this->user_has_user))
			foreach(CollectionUser_has_user::newInstance()
					->findAllAssoc($this->getId()) as $v)
			$this->addUser_has_user($v);
		return $this->user_has_user;
	}

	public function addUser_has_user(ModelUser_has_user $user_has_user) {
		$this->user_has_user[$user_has_user->getUser_id2()] = $user_has_user;
		return $this;
	}

	public function delUser_has_user(ModelUser_has_user $relation) {
		unset($this->user_has_user[$relation->getUser_id2()]);
		return $this;
	}

	public function getSkill($id) {
		if($id instanceof ModelSkill)
			$id = $id->getId();
		$arr = $this->getSkills();
		return isset($arr[$id]) ? $arr[$id] : false;
	}

	public function getSkills() {
		if(empty($this->skills))
			foreach(CollectionSkill::newInstance()
					->findAllBy('user_id', $this->getId()) as $v)
			$this->addSkill($v);
		return $this->skills;
	}

	public function addSkill(ModelSkill $skill) {
		$this->skills[$skill->getId()] = $skill;
		return $this;
	}

	public function delSkill(ModelSkill $skill) {
		unset($this->skills[$skill->getId()]);
		return $this;
	}

	public function getPhoto($id) {
		if($id instanceof ModelPhoto)
			$id = $id->getId();
		$arr = $this->getPhotos();
		return isset($arr[$id]) ? $arr[$id] : false;
	}

	public function getPhotos() {
		if(empty($this->photos))
			foreach(CollectionPhoto::newInstance()
					->findAllBy('user_id', $this->getId()) as $v)
			$this->addPhoto($v);
		return $this->photos;
	}

	public function addPhoto(ModelPhoto $photo) {
		$this->photos[$photo->getId()] = $photo;
		return $this;
	}
	
	public function delPhoto(ModelPhoto $photo) {
		unset($this->photos[$photo->getId()]);
		return $this;
	}

	public function setProfile(ModelProfile $profile) {
		$this->profile = $profile;
	}

	public function getProfile() {
		if($this->profile == null)
			if(!($this->profile = CollectionProfile::newInstance()->find($this->getId()))) // shouldnt happen!
				$this->profile = ModelProfile::newInstance()->hydrate(array('user_id' => $this->getId()));
		return $this->profile;
	}

	public function isAllowedToSee(ModelUser $user, $parameter) {
		if($parameter instanceof ModelParameter)
			$parameter = $parameter->getName();
		$profile = $user->getProfile();

		if($profile->getParameter($parameter)->getVisibility() == 'public')
			return true;
		elseif($profile->getParameter($parameter)->getVisibility() == 'amis')
			return ($user->getUser_has_user($this)->checkType(ModelUser_has_user::FRIE)
					/*&& $this->getUser_has_user($user)->checkType(ModelUser_has_user::FRIE)*/);
					// FIXME do we care about both relations?
		else
			return false;
	}

	static public function getPersistentId() {
		return array('id');
	}

	public function getPersistentData() {
		return $this->getPersistentDataNatural(array('login' ,'hash', 'inBounds', 'outBounds'));
	}

	public function getCascadedItems() {
		return array_merge($this->getSkills(), $this->getPhotos());
	}
}
