<?php
class CollectionAction extends Collection {
	public function countDesc() {
		$sth = ServiceDb::getInstance()->prepare('
				select `action`.*
				,`user`.*
				,count(*) as `nb`
				from `action`
				left join `user`
				on `user`.`id`=`action`.`user_id`
				group by `action`.`user_id`
				order by `nb` desc');
		$sth->execute();

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$a = new ModelUser();
			$a->hydrate($data);
			$b = new ModelAction();
			$b->hydrate($data);
			$b->setUser($a);
			$arr[] = array($b, $data['nb']);
		}
		return $arr;
	}
}
