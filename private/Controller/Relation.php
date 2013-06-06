<?php
class ControllerRelation extends Controller {
	public function execute($args = null) {
		$p = ServiceRenderJson::newInstance();
		if(isset($_POST['id'], $_POST['type'], $_POST['action']))
			if(!$u = CollectionUser::newInstance()->find($_POST['id']))
				$p->setData('success', false)->setData('message', 'Membre inconnu');
			else {
				$c = ($_POST['action'] == 'add' ? 'enable' : 'disable').'Type';
				$r = ServiceAuth::getInstance()->getUser()->getUser_has_user($u);
				$r->$c($_POST['type']);
				if(ServiceDb::getInstance()->persist($r)) {
					$p->setData('success', true)
					->setData('message', 'La relation a bien été '.
							($_POST['action'] == 'add' ? 'ajoutée' : 'supprimée'));

					ServiceDb::getInstance()->persist(
							ModelAction::newInstance()
							->setUser_id(ServiceAuth::getInstance()->getUser()->getId())
							->setObject('relation')
							->setValue($u->getLogin().' ('.ModelUser_has_user::$shortNames[$_POST['type']].')')
							->setType(($_POST['action'] == 'add' ? 'create' : 'delete'))
							->setWhen());

					if($r->getType() == 0) {
						ServiceAuth::getInstance()->getUser()->delUser_has_user($r);
						ServiceDb::getInstance()->delete($r);
					} else
						ServiceAuth::getInstance()->getUser()->addUser_has_user($r);
				} else
					$p->setData('message', 'L\'opération a échoué !');
			}
		else
			$p->setData('success', false);
		$p->render();
	}
}
