<?php
class ServiceMessage extends Service {
	private $messages;

	public function __construct() {
		if(!isset($_SESSION['messages']))
			$_SESSION['messages'] = array();
		$this->messages =& $_SESSION['messages'];
	}

	public function addMessage($message, $level = 'info') {
		$this->messages[] = array($message, $level);
	}

	public function getMessages() {
		$a = $this->messages;
		$this->messages = array();
		return $a;
	}

	public function hasMessages() {
		return !empty($this->messages);
	}
}