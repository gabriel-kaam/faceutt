<?php
abstract class Objectx {
	static public function newInstance() {
		return new static();
	}
}
