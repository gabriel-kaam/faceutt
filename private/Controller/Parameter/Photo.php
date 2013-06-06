<?php
class ControllerParameterPhoto extends Controller {
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
		$o = ModelPhoto::newInstance()->hydrate(array(
					'id'		=> null,
					'user_id'	=> $i));

		if(isset($_FILES['value']))
			if($_FILES['value']['error'] != 0)
				$p->setData('success', false)
				->setData('message', 'L\'upload a échoué !');
			else
				if(ServiceDb::getInstance()->persist($o)) {
					$p->setData('success', true)
					->setData('message', 'La photo a bien été ajoutée');

					$u->addPhoto($o->setId(ServiceDb::getInstance()->lastInsertId()));
					$p->setData('id', $o->getId());

					list(, $ext) = explode('/', $_FILES['value']['type'], 2); // OMG can I upload PHP files sir????
					$f = '../public/uploads/'.$o->getId();//.'.'.$ext;
					
					if(move_uploaded_file($_FILES['value']['tmp_name'], $f) && chmod($f, 0644))
						ServiceDb::getInstance()->persist(
								ModelAction::newInstance()
								->setUser_id($i)
								->setType('create')
								->setObject('photo')
								->setValue($o->getId())
								->setWhen());
					else
						$p->setData('success', false)
						->setData('message', 'Erreur lors de la copie de l\'image');
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
			if(!$o = $u->getPhoto($v))
				$p->setData('success', false)
				->setData('message', 'Cette photo n\'existe pas !');
			elseif($o->getUser_id() != $i)
				$p->setData('success', false)
				->setData('message', 'Cette photo n\'est pas la votre !');
			else
				if(!ServiceDb::getInstance()->delete($o))
					$p->setData('message', 'L\'opération a échoué !');
				else {
					$p->setData('success', true)
					->setData('message', 'La photo a bien été supprimée');

					$u->delPhoto($o);
					$f = '../public/uploads/'.$o->getId();//.'.'.$ext;

					if(@unlink($f))
						ServiceDb::getInstance()->persist(
								ModelAction::newInstance()
								->setUser_id($i)
								->setType('delete')
								->setObject('photo')
								->setValue($o->getId())
								->setWhen());
					else
						$p->setData('success', false)
						->setData('message', 'Erreur lors de la suppression de l\'image');
				}
		else
			$p->setData('success', false);

		$p->render();
	}
}
