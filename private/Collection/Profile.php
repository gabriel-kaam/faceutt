<?php
class CollectionProfile extends Collection {
	public function find($ids) {
		if(!is_array($ids))
			$ids = array($ids);
		
		$sth = ServiceDb::getInstance()->prepare('
				select `COLUMN_NAME`,`COLUMN_TYPE`
				from `information_schema`.`columns`
				where `TABLE_SCHEMA`=?
				and TABLE_NAME=?');
		$sth->execute(array('faceutt', 'profile')); // TODO

		foreach($sth->fetchAll() as $v)
			$type[$v['COLUMN_NAME'].'T'] = $v['COLUMN_TYPE'];

		$sth = ServiceDb::getInstance()->prepare('
				select `profile`.*
				from `profile`
				where `'.implode('`=? and `',ModelProfile::getPersistentId()).'`=?
				limit 1');
		$sth->execute($ids);

		if($data = $sth->fetch()) {
			$a = new ModelProfile();
			$a->hydrate(array_merge($data, $type));
			return $a;
		} return false;
	}

	public function findAllWithUser() {
		$c = static::getModelName();
		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*, `user`.*
				from `'.static::getTableName().'`
				left join `user` on `user`.`id`=`'.static::getTableName().'`.`user_id`');
		$sth->execute();
	
		$arr = array();
		foreach($sth->fetchAll() as $data) {
			if($data['id'] == ServiceAuth::getInstance()->getUser()->getId())
				continue;

			$a = new ModelUser();
			$a->hydrate($data);
			$b = new $c();
			$b->hydrate($data);
			$a->setProfile($b);
			$arr[] = $a;
		}
		return $arr;
	}

	public function findByWithUser($names, $ids) {
		if(!is_array($ids))
			$ids = array($ids);
		if(!is_array($names))
			$names = array($names);

		$c = static::getModelName();
		$sth = ServiceDb::getInstance()->prepare('
				select `'.static::getTableName().'`.*, `user`.*
				from `'.static::getTableName().'`
				left join `user` on `user`.`id`=`'.static::getTableName().'`.`user_id`
				where `'.implode('`=? and `', $names).'`=?');
		$sth->execute($ids);

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			if($data['id'] == ServiceAuth::getInstance()->getUser()->getId())
				continue;

			$a = new ModelUser();
			$a->hydrate($data);
			$b = new $c();
			$b->hydrate($data);
			$a->setProfile($b);
			$arr[] = $a;
		}
		return $arr;
	}
}
