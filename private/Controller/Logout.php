<?php
class ControllerLogout extends Controller {
	public function execute($args = null) {
		setcookie('login', '', -1);
		setcookie('value', '', -1);
		ServiceAuth::getInstance()->deAuth();
		$v = $this->getQueryNext();
		if($v != 'quiet')
			ServiceMessage::getInstance()->addMessage('Vous avez bien été déconnecté', 'success');
		header('Location: /home');
	}
}
