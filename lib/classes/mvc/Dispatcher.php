<?php
	class Dispatcher {
		private $module;
		private $moduleCode;
		private $actionCode;
  
		function __construct($module, $action) {		
			$this->initModule($module);
			$this->setActionCode($action);		
		}
		
		private function initModule($moduleCode) {		
			$class = ucfirst($moduleCode).'Actions';
			if (!class_exists($class))
				throw new Exception('module '.$moduleCode.' not exists');			
			$this->moduleCode = $moduleCode;
			$this->module = new $class();
		}
		
		private function setActionCode($actionCode) {		
			if (!$this->module)
				throw new Exception('module must be initialized');
			$this->actionCode = $actionCode;	
			$actionMethod =  $this->getActionMethod($actionCode);
			if (!method_exists($this->module, $actionMethod)) {
				throw new Exception('action method '.$actionMethod.' not exists');
			}		
		}
		
		private function getActionMethod() {
			return 'execute'.ucfirst($this->actionCode);
		}	
		
		public function getData() {
			$actionMethod = $this->getActionMethod();
			$data = $this->module->$actionMethod();
			return $data;
		}
	}