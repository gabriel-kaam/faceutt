<?php
class ControllerHome extends Controller {
	public function execute($args = null) {
		if(ServiceAuth::getInstance()->isAuth())
			header('Location: /profile');
		else
			header('Location: /login');
	}
}
