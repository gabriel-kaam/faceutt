<?php
abstract class Collection extends Objectx {
	static public function getModelName() {
		return str_replace('Collection', 'Model', get_called_class());
	}

	static public function getTableName() {
		return strtolower(str_replace('Collection', '', get_called_class()));
	}

	public function find($ids) {
		if(!is_array($ids))
			$ids = array($ids);

		$c = static::getModelName();
		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*
				from `'.static::getTableName().'`
				where `'.implode('`=? and `',$c::getPersistentId()).'`=?
				limit 1');
		$sth->execute($ids);

		if($data = $sth->fetch()) {
			$a = new $c();
			$a->hydrate($data);
			return $a;
		} return false;
	}

	public function findAll() {
		$c = static::getModelName();
		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*
				from `'.static::getTableName().'`');
		$sth->execute();

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$a = new $c();
			$a->hydrate($data);
			$arr[] =  $a;
		}
		return $arr;
	}

	public function findBy($names, $ids) {
		if(!is_array($ids))
			$ids = array($ids);
		if(!is_array($names))
			$names = array($names);

		$c = static::getModelName();
		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*
				from `'.static::getTableName().'`
				where `'.implode('`=? and `', $names).'`=?
				limit 1');
		$sth->execute($ids);

		if($data = $sth->fetch()) {
			$a = new $c();
			$a->hydrate($data);
			return $a;
		} return false;
	}

	public function findAllBy($names, $ids) {
		if(!is_array($ids))
			$ids = array($ids);
		if(!is_array($names))
			$names = array($names);

		$c = static::getModelName();
		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*
				from `'.static::getTableName().'`
				where `'.implode('`=? and `', $names).'`=?');
		$sth->execute($ids);

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$a = new $c();
			$a->hydrate($data);
			$arr[] = $a;
		}
		return $arr;
	}

	// for 1...n assoc
	/* useless?
	public function findAllAssocBy($names, $ids) {
		if(!is_array($ids))
			$ids = array($ids);
		if(!is_array($names))
			$names = array($names);

		$c = array_pop(explode('_', static::getTableName())); // user_has_skill => skill
		$C = 'Model'.ucfirst($c);
		$d = array_pop($C::getPersistentId());

		$sth = ServiceDb::getInstance()->prepare('
				select `'.$c.'`.*
				from `'.static::getTableName().'`
				join `'.$c.'` on `'.$c.'`.`'.$d.'`=`'.static::getTableName().'`.`'.$c.'_'.$d.'`
				where `'.static::getTableName().'`.`'.implode('`,=?,`'.static::getTableName().'`.`', $names).'`=?');
		$sth->execute($ids);

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$a = new $C();
			$a->hydrate($data);
			$arr[] = $a;
		}
		return $arr;
	}
	 */

	// for n...m assoc
	public function findAllAssoc($ids) {
		if(!is_array($ids))
			$ids = array($ids);

		$c = static::getModelName();
		$t = $c::getPersistentId();
		$d1 = array_pop($t);
		$d2 = array_pop($t);
		$e = array_pop(explode('_', static::getTableName())); // user_has_skill => skill
		$E = 'Model'.ucfirst($e);
		$k = array_pop($E::getPersistentId());

		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*,`'.$e.'`.*
				from `'.static::getTableName().'`
				left join `'.$e.'` on `'.static::getTableName().'`.`'.$d1.'`=`'.$e.'`.`'.$k.'`
				where `'.static::getTableName().'`.`'.$d2.'`=?');
		$sth->execute($ids);

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$z = 'set'.ucfirst($e).'2';
			$a = new $c();
			$a->hydrate($data);
			$x = new $E();
			$x->hydrate($data);
			$a->$z($x);
			$arr[] = $a;
		}
		return $arr;
	}
}
