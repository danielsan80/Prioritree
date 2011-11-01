<?php
interface iView {
	
	public function setData($data=array());
	public function setTpl($tpl);
	public function render($data=null);
	
}