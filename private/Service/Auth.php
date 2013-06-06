<?php
class ServiceAuth extends Service {
	private $login;
	private $admin = false;
	private $auth = false;
	private $user;

	static public function getInstance() {
		if(empty($_SESSION['ServiceAuth']))
			$_SESSION['ServiceAuth'] = new static();
		return $_SESSION['ServiceAuth'];
	}

	public function getLogin() {
		return $this->login;
	}

	public function setLogin($login) {
		$this->login = $login;
		return $this;
	}

	public function isAdmin() {
		return $this->admin;
	}

	public function setAdmin($admin) {
		$this->admin = $admin;
		return $this;
	}

	public function isAuth() {
		return $this->auth || $this->cookieAuth();
	}

	public function setAuth($auth) {
		$this->auth = $auth;
		return $this;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function deAuth() {
		$_SESSION['ServiceAuth'] = null;
		unset($_SESSION['ServiceAuth']);
	}
	
	public static function createHash($plain) {
		return md5($plain);
	}

	public function regularAuth($login, $pass, $hashed = false) {
		if($user = CollectionUser::newInstance()->findBy(
				array('login', 'hash'),
				array($login, $hashed ? $pass : self::createHash($pass)))) {
			session_regenerate_id(true);
			$this->user = $user;
			$this->auth = true;
			$this->admin = false;
			return true;
		} return false;
	}

	public function cookieAuth() {
		if(isset($_COOKIE['login'], $_COOKIE['hash']))
			if($this->regularAuth($_COOKIE['login'], $_COOKIE['hash'], true))
				return true;
			else
				$this->deAuth();
		return false;
	}

	public function adminAuth($pass) {
		if($pass == 'admin') {
			session_regenerate_id(true);
			//$this->auth = true;
			$this->admin = true;
			return true;
		} return false;
	}
}
