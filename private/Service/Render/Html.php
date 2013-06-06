<?php
class ServiceRenderHtml extends ServiceRender {
	private $file;

	// TODO should be put into a file ! (translation powa)
	static public $longNames =  array(
			'last_name'		=> 'nom',
			'first_name'	=> 'prénom',
			'sex'			=> 'sexe',
			'semester'		=> 'semestre',
			'prog'			=> 'programme',
			'prive'			=> 'personne',
			'public'		=> 'tout le monde',
			'amis'			=> 'mes amis',
			'work'			=> 'collègue',
			'know'			=> 'connaissance',
			'friend'		=> 'ami',
			'like'			=> 'coup de coeur'
	);

	public function load($file) {
		$this->setFile($file);
		return $this;
	}

	public function getFile() {
		return $this->file;
	}

	public function render() {
		$hideNavigation	= false;
		$hideMessages	= false;

		extract(parent::getData());
		
		$_POST = @array_map_recursive('htmlspecialchars', $_POST);
		$_GET = @array_map_recursive('htmlspecialchars', $_GET);

		require 'View/header.php';
		if(!$hideNavigation)
			require ServiceAuth::getInstance()->isAdmin() ? 'View/admin/nav.php' : 'View/nav.php';
		require 'View/body.php';

		echo '<div id="messages"></div>';
		if(!$hideMessages && ServiceMessage::getInstance()->hasMessages())
			foreach(ServiceMessage::getInstance()->getMessages() as $v) {
				list($message, $level) = $v;
				require 'View/message.php';
			}

		require 'View/'.$this->getFile().'.php';
		require 'View/footer.php';

		return $this;
	}

	private function setFile($file) {
		if(file_exists("View/$file.php"))
			$this->file = $file;
		else
			throw new RessourceNotFound("Template - $file");
	}
}
