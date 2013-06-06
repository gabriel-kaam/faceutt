<?php
class ControllerSearch extends Controller {
	public function execute($args = null) {
		if(!ServiceAuth::getInstance()->isAuth())
			header('Location: /login');
		else {
			if(empty($_POST))
				return ServiceRenderHtml::newInstance()->load('search')->render();

			$res = array();
			if(isset($_POST['search1']))
				$res = CollectionProfile::newInstance()->findByWithUser(
						'prog',
						$_POST['prog']
				);
			elseif(isset($_POST['search2']))
				$res = CollectionProfile::newInstance()->findByWithUser(
						array('prog', 'semester'),
						array($_POST['prog'], $_POST['semester'])
				);
			elseif(isset($_POST['search3']))
				$res = CollectionProfile::newInstance()->findByWithUser(
						'sex',
						$_POST['sex']
				);
			elseif(isset($_POST['search4'])) {
				$resx = CollectionUser_has_user::newInstance()->findByWithUserLoose(
						'user_id1',
						ServiceAuth::getInstance()->getUser()->getId()
				);
				foreach($resx as $v)
					$res[] = $v->getUser2();
			} elseif(isset($_POST['search5']))
				$res = CollectionProfile::newInstance()->findAllWithUser();
			else
				return ControllerError::newInstance()->execute($args);

			$_SESSION['search'] = $res;
			header('Location: /search/result');
		}
	}
}
