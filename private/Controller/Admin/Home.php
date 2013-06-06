<?php
class ControllerAdminHome extends Controller {
	public function execute($args = null) {
		if(!ServiceAuth::getInstance()->isAdmin())
			return header('Location: /admin/login');
		
		ServiceRenderHtml::newInstance()->load('admin/home')->render();
	}
}
