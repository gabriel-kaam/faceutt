<?php
class ControllerAdminLogs extends Controller {
	public function execute($args = null) {
		if(!ServiceAuth::getInstance()->isAdmin())
			return header('Location: /admin/login');
		
		$res = CollectionAction::newInstance()->findAll();
		rsort($res);
		ServiceRenderHtml::newInstance()->load('admin/logs')
		->setData('res', $res)
		->render();
	}
}
