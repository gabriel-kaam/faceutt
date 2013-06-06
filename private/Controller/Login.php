<?php
class ControllerLogin extends Controller {
	public function execute($args = null) {
		if(ServiceAuth::getInstance()->isAuth())
			die(header('Location: /home'));
		elseif(ServiceAuth::getInstance()->isAdmin())
			die(header('Location: /admin'));
		if(!empty($_POST))
			if(ServiceAuth::getInstance()->regularAuth($_POST['login'], $_POST['password'])) {
				if(isset($_POST['remember_me'])) {
					setcookie('login', ServiceAuth::getInstance()->getUser()->getLogin(), time()+3600*24*365);
					setcookie('hash', ServiceAuth::getInstance()->getUser()->gethash(), time()+3600*24*365);
				}
				ServiceMessage::getInstance()->addMessage('Vous avez bien été connecté', 'success');
				header('Location: /home');
				return;
			} else
				ServiceMessage::getInstance()->addMessage('Mauvaise combinaison', 'error');

		ServiceRenderHtml::newInstance()->load('login')
		->setData('hideNavigation', true)
		->render();
	}
}
