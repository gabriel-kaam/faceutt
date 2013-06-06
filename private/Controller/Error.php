<?php
class ControllerError extends Controller {
	public function execute($args = null) {
		$p = ServiceRenderHtml::newInstance()->load('error')
		->setData('e', $args)
		->setData('hideMessages', true);
		if(!ServiceAuth::getInstance()->isAuth())
			$p->setData('hideNavigation', true);
		$p->render();
	}
}