<?php
class ServiceDb extends Service {
	private $pdo;
	
	private $dbhost = 'localhost';
	private $dbname = 'faceutt';
	private $dbuser = 'faceutt';
	private $dbpass = 'jnu3jr2KencNeXjD';

	protected function __construct() {
		// TODO , create getters, setters for this .. lazy rite now
		$this->pdo = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname, $this->dbuser, $this->dbpass);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $this;
	}

	public function __call($name, $args) {
		return call_user_func_array(array($this->pdo, $name), $args);
	}

	public function persist(Persistable $instance) {
		$t = strtolower(str_replace('Model', '', get_class($instance)));

		$k = array_merge($instance::getPersistentId(), array_keys($instance->getPersistentData()));
		$sth = ServiceDb::getInstance()->prepare('
				replace into `'.$t.'`
				(`'.implode('`,`', $k).'`)
				values
				('.implode(',', array_fill(0, count($k), '?')).')');

		foreach($instance::getPersistentId() as $V) {
			$c = getGetter($V);
			$v[] = $instance->$c();
		}
		$v = array_merge($v, array_values($instance->getPersistentData()));
		$a = $sth->execute($v);
		
		if($b = $instance->getCascadedItems())
			foreach($b as $v)
				$a = $a && $this->persist($b);

		return $a;
	}

	public function delete(Persistable $instance) {
		$t = strtolower(str_replace('Model', '', get_class($instance)));

		$sth = ServiceDb::getInstance()->prepare('
				delete from `'.$t.'`
				where `'.implode('`=? and `',$instance::getPersistentId()).'`=?
				limit 1');

		foreach($instance::getPersistentId() as $V) {
			$c = getGetter($V);
			$v[] = $instance->$c();
		}
		$a = $sth->execute($v);
		return $a;
	}
}
