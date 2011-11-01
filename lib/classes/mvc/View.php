<?php
class View implements iView {
	private $cache = 'cache/twig';
	private $tpl;
	private $data;
	
	public function __construct() {
		$this->data = array();
	}
	
	public function setData($data=array()) {
		if (!$this->isArray($data))
			throw new Exception('Argument is not an Array!');
		$this->data = $data;
	}
	
	public function setTpl($tpl) {
		if (!file_exists($tpl)) throw new Exception('File not found!');
		$this->tpl = $tpl;
	}
	
	public function render($data=null) {
		if (!is_null($data)) $this->setData($data);
		$loader = new Twig_Loader_Filesystem(Config::getROOT().'.');
		$twig = new Twig_Environment($loader, array('cache' => 'cache/twig'));		
		$tpl = $twig->loadTemplate($this->tpl);
		return $tpl->render($this->data);
	}

	private function isArray($array) {
		return is_array($array) || ($array instanceof ArrayAccess && $array instanceof Iterator );
	}
}