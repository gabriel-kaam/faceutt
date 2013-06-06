<?php
class ControllerAdminLogin extends Controller {
	public function execute($args = null) {
		if(ServiceAuth::getInstance()->isAdmin())
			die(header('Location: /admin'));

		if(!empty($_POST))
			if(ServiceAuth::getInstance()->adminAuth($_POST['password'])) {
				ServiceMessage::getInstance()->addMessage('Vous avez bien été connecté', 'success');
				header('Location: /admin');
				return;
			} else
				ServiceMessage::getInstance()->addMessage('Mauvaise mot de passe', 'error');

		ServiceRenderHtml::newInstance()->load('admin/login')
		->setData('hideNavigation', true)
		->render();
	}
}
