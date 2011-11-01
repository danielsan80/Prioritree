<?php
class AutoloaderRegister {
	private static $instance = null;		
	private $register;		
	private $cachefile;		

	public static function getInstance($cachefile=null) {
		if(self::$instance == null) {
			$class = __CLASS__;
			self::$instance = new $class();
		}
		self::$instance->setCachefile($cachefile);
		return self::$instance;
	}
	
	private function setCachefile($cachefile) {		
		if (!$this->cachefile && !$cachefile) throw new Exception('getInstance() need a cachefile');  
		if ($cachefile==$this->cachefile) return;
		touch($cachefile);
		$register = file_get_contents($cachefile);
		$this->register = $register?unserialize($register):array(); 
		$this->cachefile = $cachefile;
	}

	private function __construct() {}
	
	public function get($class){ return $this->register[$class]; }
	public function set($class, $filename){
		$this->register[$class] = $filename;
		file_put_contents($this->cachefile,serialize($this->register));
	}
	public function clear() {
		@unlink($this->cachefile);
		self::$instance = null;
		$this->register = array();
	}
}