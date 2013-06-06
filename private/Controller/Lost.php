<?php
class ControllerLost extends Controller {
	public function execute($args = null) {
		$p = ServiceRenderHtml::newInstance()->load('lost')
		->setData('page', $args)
		->setData('hideMessages', true);
		if(!ServiceAuth::getInstance()->isAuth())
			$p->setData('hideNavigation', true);
		$p->render();
	}
}
