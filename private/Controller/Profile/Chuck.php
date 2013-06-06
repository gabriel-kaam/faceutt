<?php
class ControllerProfileChuck extends Controller {
	public function execute($args = null) {
		ServiceRenderHtml::newInstance()->load('chuck')->render();
	}
}
