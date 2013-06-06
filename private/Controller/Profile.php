<?php
class ControllerProfile extends Controller {
	public function execute($args = null) {
		if(!ServiceAuth::getInstance()->isAuth())
			header('Location: /login');
		else {
			$p = ServiceRenderHtml::newInstance()->load('profile')
				->setData('user', ServiceAuth::getInstance()->getUser())
				->setData('guest', false);

			if($q = $this->getQueryNext())
				if($q != ServiceAuth::getInstance()->getUser()->getLogin())
					if($user = CollectionUser::newInstance()->findBy('login', $q))
						$p->setData('user', $user)
						->setData('guest', true);
					else
						$p->load('profile_not_found');

			$p->render();
		}
	}
}
