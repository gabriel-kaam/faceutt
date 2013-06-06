<?php
class ControllerSearchResult extends Controller {
	public function execute($args = null) {
		if(!ServiceAuth::getInstance()->isAuth())
			header('Location: /login');
		else {
			if(isset($_SESSION['search']))
				$p = ServiceRenderHtml::newInstance()->load('search_result')
				->setData('result', $_SESSION['search'])
				->render();
			else
				header('Location: /search');
		}
	}
}
