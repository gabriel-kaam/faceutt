<?php
class ControllerAdminStats extends Controller {
	public function execute($args = null) {
		if(!ServiceAuth::getInstance()->isAdmin())
			return header('Location: /admin/login');

		ServiceRenderHtml::newInstance()->load('admin/stats')
		->setData('res1', CollectionAction::newInstance()->countDesc())
		->setData('res2', CollectionUser::newInstance()->findAllWithReputation())
		->setData('res3', CollectionUser_has_user::newInstance()->findAllCoworkers())
		->render();
	}
}
