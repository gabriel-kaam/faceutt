<?php
class ControllerParameterSkill extends Controller {
	public function execute($args = null) {
		$v = $this->getQueryNext();
		if(in_array($v, array('add', 'del')))
				$this->$v($args);
		else
			ControllerError::newInstance()->execute();
	}

	private function add($args) {
		$p = ServiceRenderJson::newInstance();
		$u = ServiceAuth::getInstance()->getUser();
		$i = $u->getId();
		$o = ModelSkill::newInstance()->hydrate(array(
					'id'		=> null,
					'user_id'	=> $i,
					'name'		=> $_POST['value']));

		if(isset($_POST['value']))
			if(ServiceDb::getInstance()->persist($o)) {
				$p->setData('success', true)
				->setData('message', 'La compétence a bien été ajoutée');

				$u->addSkill($o->setId(ServiceDb::getInstance()->lastInsertId()));
				$p->setData('id', $o->getId());

				ServiceDb::getInstance()->persist(
						ModelAction::newInstance()
						->setUser_id($i)
						->setType('create')
						->setObject('skill')
						->setValue($o->getName())
						->setWhen());
			} else
				$p->setData('message', 'L\'opération a échoué !');
		else
			$p->setData('success', false);

		$p->render();
	}

	private function del($args) {
		$p = ServiceRenderJson::newInstance();
		$u = ServiceAuth::getInstance()->getUser();
		$i = $u->getId();

		if($v = $this->getQueryNext())
			if(!$o = $u->getSkill($v))
				$p->setData('success', false)
				->setData('message', 'Vous n\'avez pas cette compétence #1');
			elseif($o->getUser_id() != $u->getId())
				$p->setData('success', false)
				->setData('message', 'Vous n\'avez pas cette compétence #2');
			else
				if(!ServiceDb::getInstance()->delete($o))
					$p->setData('message', 'L\'opération a échoué !');
				else {
					$p->setData('success', true)
					->setData('message', 'La compétence a bien été supprimée');

					$u->delSkill($o);

					ServiceDb::getInstance()->persist(
							ModelAction::newInstance()
							->setUser_id($i)
							->setType('delete')
							->setObject('skill')
							->setValue($o->getName())
							->setWhen());
				}
				
		else
			$p->setData('success', false);

		$p->render();
	}
}
