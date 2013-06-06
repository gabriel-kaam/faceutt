<?php
class CollectionUser_has_user extends Collection {
	public function findByWithUserLoose($names, $ids) {
		if(!is_array($names))
			$names = array($names);
		if(!is_array($ids))
			$ids = array($ids);

		$k = '';
		foreach(array_keys(ModelUser::newInstance()->getPersistentData()) as $v)
			$k .= "`user2`.`$v` `a__$v`,";

		$sth = ServiceDb::getInstance()->prepare('
				select `user_has_user`.*,
				'.$k.'
				`user2`.`id` `a__id`
				from `user_has_user`
				left join `user` `user2` on `user2`.`id`=`user_has_user`.`user_id2`
				where `'.implode('`=? or `', $names).'`=?');
		$sth->execute($ids);

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			if($data['a__id'] == ServiceAuth::getInstance()->getUser()->getId())
				continue;

			$datax = array();

			foreach($data as $k => $v)
				if(strpos($k, 'a__') === 0)
					$datax[str_replace('a__', '', $k)] = $v;

			$a = ModelUser_has_user::newInstance()->hydrate($data);
			$a->setUser2(ModelUser::newInstance()->hydrate($datax));
			$arr[] = $a;
		}
		return $arr;
	}
	
	public function findAllCoworkers() {
		$k = '';
		foreach(array_keys(ModelUser::newInstance()->getPersistentData()) as $v)
			$k .= "`user1`.`$v` `a__$v`, `user2`.`$v` `b__$v`,";
		
		$sth = ServiceDb::getInstance()->prepare('
				select `user_has_user`.*,
				'.$k.'
				`user1`.`id` `a__id`,
				`user2`.`id` `b__id`
                                from `user_has_user`
                                left join `user` `user1` on `user1`.`id`=`user_has_user`.`user_id1`
                                left join `user` `user2` on `user2`.`id`=`user_has_user`.`user_id2`
                                having 2 = (
                                        select count(*)
                                        from `user_has_user` `foo`
                                        where (`foo`.`type` & '.ModelUser_has_user::WORK.' ) && ( (
                                                        `user_has_user`.`user_id1`=`foo`.`user_id1`
                                                        and `user_has_user`.`user_id2`=`foo`.`user_id2`
                                                ) OR (
                                                        `user_has_user`.`user_id1`=`foo`.`user_id2`
                                                        and `user_has_user`.`user_id2`=`foo`.`user_id1`
                                                ) )
                                      )');
		$sth->execute();
		
		$pairs = array();
		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$datax = array();

			// FIXME : find another way to avoir doublons
			if(in_array($data['a__id'].'.'.$data['b__id'], $pairs)
					|| in_array($data['b__id'].'.'.$data['a__id'], $pairs))
				continue;
			$pairs[] = $data['a__id'].'.'.$data['b__id'];

			$a = ModelUser_has_user::newInstance()->hydrate($data);
			foreach($data as $k => $v)
				if(strpos($k, 'a__') === 0)
					$datax[str_replace('a__', '', $k)] = $v;
			$a->setUser1(ModelUser::newInstance()->hydrate($datax));

			foreach($data as $k => $v)
				if(strpos($k, 'b__') === 0)
					$datax[str_replace('b__', '', $k)] = $v;
			$a->setUser2(ModelUser::newInstance()->hydrate($datax));

			$arr[] = $a;
		}
		return $arr;
	} 
}
