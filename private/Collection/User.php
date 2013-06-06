<?php
class CollectionUser extends Collection {
	public function findAllWithReputation() {
		$sth = ServiceDb::getInstance()->prepare('
				select `user`.*, (`inBounds`/(`inBounds`+`outBounds`)) `reputation`
				from `user`
				order by `reputation` desc, `inBounds` desc');
		$sth->execute();

		$arr = array();
		foreach($sth->fetchAll() as $data) {
			$a = new ModelUser();
			$a->hydrate($data);
			$arr[] =  $a;
		}
		return $arr;
	}
}
