<?php
interface Persistable {
	static public function getPersistentId();
	public function getPersistentData();
	public function getCascadedItems();
}
