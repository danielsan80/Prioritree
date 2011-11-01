<?php
/**
 * 
 * @todo passare opzionalmente il varname al costruttore
 *
 */

	class SessionRegister implements Observer {
		private $observable;
		private $varname;
		private $session;
		
		public function __construct(Observable $observable, $varname=null) {
			$this->observe($observable);
			$varname = $varname?__CLASS__.'.'.$varname:(__CLASS__.'.'.get_class($observable));
			$this->varname = $varname;
			if (!isset($_SESSION[$varname])) $_SESSION[$varname] = new stdClass();
			$this->session = $_SESSION[$varname];			
		}
		
		public function set($key, $value) {
			$this->session->$key = serialize($value);			
		}
		public function get($key, $default = null) {
			if ($value = unserialize($this->session->$key))
				return $value;
			else
				return $default; 			
		}
		public function clr($key) {
			empty($this->session->$key);
		}
		
		private function strToArray($str){
			$str = explode(',',$str);
			foreach($str as $k => $v) $str[$k] = trim($v);
			return $str;
		}
		
	//Observer
		public function update($toUpdateProperties=null) {			
			if ($toUpdateProperties) $toUpdateProperties = $this->strToArray($toUpdateProperties);
			$properties = $this->observable->getObservableProperties();
			
			if ($toUpdateProperties)
				$properties = array_intersect($properties,$toUpdateProperties);
			
			foreach($properties as $property) {
				$method = 'get'.ucfirst($property);
				$this->set($property, $this->observable->$method());
			}
		}
		
		public function observe(Observable $observable) {
			if ($this->observable) $this->observable->removeObserver($this);
			$observable->registerObserver($this);
			$this->observable = $observable;
		}
	}