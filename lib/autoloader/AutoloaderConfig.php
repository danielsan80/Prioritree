<?php
require_once(dirname(__FILE__).'/BaseAutoloaderConfig.php');

class AutoloaderConfig extends BaseAutoloaderConfig {
	
	public function configure(){
		$this->setRoot(ROOT);
		$this->setCachefile('cache/classRegister.txt');
		$this->addPath('lib/classes/',true);
		$this->addPath('lib/sfYaml/lib/',true);
		$this->addPath('test/lib/classes/',true);
		//$this->addClassFilenameMethod('symfony');
	}
	
	protected function getClassFilename_symfony($class){	
		return $class.'.class.php';
	}
}
