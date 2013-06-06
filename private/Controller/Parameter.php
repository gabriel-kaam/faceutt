<?php
class ControllerParameter extends Controller {
	public function execute($args = null) {
		$v = $this->getQueryNext();
		if(in_array($v, array('update', 'updatev')))
				$this->$v($args);
		else
			ControllerError::newInstance()->execute();
	}

	private function update($args) {
		$p = ServiceRenderJson::newInstance();
		$pro = ServiceAuth::getInstance()->getUser()->getProfile();

		if(isset($_POST['id'], $_POST['value']))
			if(!$u = $pro->getParameter($_POST['id']))
				$p->setData('success', false)->setData('message', 'Paramètre inconnu');
			else
				if(!$u->setValue($_POST['value']))
					$p->setData('message', 'Valeur non autorisée !');
				else
					if(ServiceDb::getInstance()->persist($pro)) {
						$p->setData('success', true)
						->setData('message', 'La valeur du paramètre a bien été modifiée');

						ServiceDb::getInstance()->persist(
								ModelAction::newInstance()
								->setUser_id(ServiceAuth::getInstance()->getUser()->getId())
								->setType('update')
								->setObject('parameter')
								->setValue($_POST['id'])
								->setWhen());
					} else
						$p->setData('message', 'L\'opération a échoué !');
		else
			$p->setData('success', false);

		$p->render();
	}

	private function updateV($args) {
		$p = ServiceRenderJson::newInstance();
		$pro = ServiceAuth::getInstance()->getUser()->getProfile();

		if(isset($_POST['id'], $_POST['value']))
			if(!$u = $pro->getParameter($_POST['id']))
				$p->setData('success', false)->setData('message', 'Paramètre inconnu');
			else {
				if(!ModelParameter::isValidVisibility($_POST['value']))
					$p->setData('success', false)->setData('message', 'Valeur incorrecte');
				else {
					$u->setVisibility($_POST['value']);
					// we persist the Profile, not the Parameter ! Be careful
					if(ServiceDb::getInstance()->persist($pro)) {
						$p->setData('success', true)
						->setData('message', 'La visibilité du paramètre a bien été modifiée');

						ServiceDb::getInstance()->persist(
								ModelAction::newInstance()
								->setUser_id(ServiceAuth::getInstance()->getUser()->getId())
								->setType('update')
								->setObject('parameter visibility')
								->setValue($_POST['id'])
								->setWhen());
					} else
						$p->setData('message', 'L\'opération a échoué !');
				}
			}
		else
			$p->setData('success', false);

		$p->render();
	}
}
