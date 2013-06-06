<?php
abstract class Controller extends Objectx {
	abstract public function execute($args = null);

	private $query;
	private $query_bak;

	public function __construct($query = null) {
		if(empty($query))
			$this->setQuery(array());
		else
			$this->setQuery($query);
	}

	private function setQuery($query) {
		$this->query_bak = $this->query = $query; 
	}

	protected function resetQuery() {
		$this->query = $this->query_bak;
	}

	protected function getQueryAll() {
		return $this->query;
	}

	protected function getQueryNext() {
		return array_shift($this->query);
	}

	public static function main(Array $query) {
		$queryU = array_map('ucfirst', $query);
		$data = array();
		while($v = array_pop($queryU)) {
			$c = 'Controller'.implode('', $queryU).$v;
			if($c != 'Controller' && class_exists($c))
				return new $c($data);
			array_unshift($data, array_pop($query));
		}
		throw new UnknownClass(implode('', $query));
	}
}
