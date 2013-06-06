<?php
class ControllerSubscribe extends Controller {
	public function execute($args = null) {
		if(!empty($_POST)) {
			$_POST['id'] = null;
			$_POST['hash'] = ServiceAuth::createHash($_POST['pass1']);
			$_POST['inBounds'] = 0;
			$_POST['outBounds'] = 0;
			$u = ModelUser::newInstance()->hydrate($_POST);
			$p = ModelProfile::newInstance();

			if($_POST['pass1'] != $_POST['pass2'])
				ServiceMessage::getInstance()->addMessage('Les mots de passes ne correspondent pas', 'error');
			elseif(CollectionUser::newInstance()->findBy('login', $_POST['login']))
				ServiceMessage::getInstance()->addMessage('Ce login est déjà utilisé', 'error');
			else
				if(ServiceDb::getInstance()->persist($u))
					if($i = ServiceDb::getInstance()->lastInsertId())
						if(ServiceDb::getInstance()->persist($p->hydrate(array('user_id' => $i)))) {
							ServiceMessage::getInstance()->addMessage('Votre compte a bien été créé', 'success');
							ServiceDb::getInstance()->persist(
									ModelAction::newInstance()
									->setUser_id($i)
									->setType('create')
									->setObject('profile')
									->setWhen());
							return header('Location: /logout/quiet');
						} else {
							ServiceDb::getInstance()->delete($u);
							ServiceMessage::getInstance()->addMessage('Une erreur est survenue #2', 'error');
						}
				else
					ServiceMessage::getInstance()->addMessage('Une erreur est survenue #1', 'error');
		}

		ServiceRenderHtml::newInstance()->load('subscribe')
		->setData('hideNavigation', true)
		->render();
	}
}
