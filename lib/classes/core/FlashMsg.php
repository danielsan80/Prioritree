<?php
/**
 * 
 * @todo fare in modo che update passi un array associativo con tutte le proprietà registrate oppure opzionalemnte 
 * con quelle che hanno subito un cambiamento 
 *
 */

	class FlashMsg implements Observable {
		private $observers;
		private $msgs;
		private static $instance = null;
	
		private function __construct(){
			$sessionRegister = new SessionRegister($this);
			$this->msgs = $sessionRegister->get('msgs',array());			
			$this->registerObservableProperty('msgs');
		}
		
		public static function getInstance() {
			if(self::$instance == null) {
				$c = __CLASS__;
				self::$instance = new $c;
			}
			return self::$instance;
		}

		public function addMsg($msg, $type='notice') {
			$this->msgs[$type][] = $msg;
			$this->save();
		}		
		public function setMsg($msg, $type='notice') {
			$this->msgs[$type] = array($msg);
			$this->save();			
		}
		public function clrMsgs($type=null) {
			$this->msgs = array();
			$this->save();			
		}
		public function clrMsgsByType($type) {
			$this->msgs[$type] = array();
			$this->save();
		}
		
		public function getMsgs() {
			return $this->msgs;
		}
		public function setMsgs($msgs) {
			$this->msgs = $msgs;
			$this->save();
		}
		
		public function getMsgsByTypeAsString($type) {
			return implode("\n", $this->msgs[$type]);
		}
		public function getMsgsAsString() {
			$out = array();
			foreach( $this->msgs as $type => $msg) $out[$type] = implode("\n", $msg);				
			return $out;			
		}
		public function getMsgsByType($type) {
			return $this->msgs[$type];
		}
		
		public function save() {
			$this->notifyObservers();			
		}
		
	//Oservable Interface	
		public function registerObserver(Observer $o) {
			$this->observers[] = $o;
		}
		public function removeObserver(Observer $o) {
			$i = array_search($o, $this->observers);
			if ( $i!==false ) {
				unset($this->observers[$i]);				
			}
		}		
		public function notifyObservers() {			
			foreach ($this->observers as $i => $o) {
				$o->update();
			}			
		}
		public function registerObservableProperty($str) {
			$this->observableProperties[] = $str;
		}
		public function removeObservableProperty($str) {
			$i = array_search($str, $this->observableProperties);
			if ( $i!==false )
				unset($this->observableProperties[$i]);
		}
		public function getObservableProperties() {
			return $this->observableProperties;
		}
		
	}