<?php
abstract class Model extends Objectx {
	public function hydrate(Array $data) {
		foreach($data as $k => $v) {
			$c = 'set'.ucfirst($k);
			if(method_exists($this, $c))
				$this->$c($v);
		}
		return $this;
	}

	protected function getPersistentDataNatural(Array $names) {
		$a = Array();
		foreach($names as $v) {
			$c = 'get'.ucfirst($v);
			$a[$v] = $this->$c();
		}
		return $a;
	}
	
	public function getCascadedItems() {
		return array();
	}
}
