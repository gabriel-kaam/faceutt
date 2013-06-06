<?php
abstract class ServiceRender extends Service {
	protected $data = array();

	abstract public function render();

	public function getData($nm = null) {
		return $nm == null ? $this->data : $this->data[$nm];
	}

	public function setData($name, $value) {
		$this->data[$name] = $value;
		return $this;
	}
}
