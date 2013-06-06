<?php
class ControllerAdmin extends Controller {
	public function execute($args = null) {
		if(ServiceAuth::getInstance()->isAdmin())
			header('Location: /admin/home');
		else
			header('Location: /admin/login');
	}
}
