<?php
class ServiceRenderJson extends ServiceRender {
	public function render() {
		echo json_encode(array_merge(
				array(
						'success'	=> false,
						'message'	=> 'Une erreur est survenue !'
				), parent::getData())
		);
		return $this;
	}
}
