<?php
class Auth implements Observable {
	private $observers;
	private static $instance = null;
	private $user;
	private $userTable;

	private function __construct() {			
		$sessionRegister = new SessionRegister($this);
		$this->user = $sessionRegister->get('user',null);			
		$this->registerObservableProperty('user');		
	}
	public static function getInstance() {
		if(self::$instance == null) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
	public function login($username, $password) {
		$userTable = $this->getUserTable();			
		$user = $userTable->getUserByAccessData($username, $password);
		if ($user) {
			$this->setUser($user);
			return true;
		} else return false;		
	}
	
	public function logout() {
		$this->unsetUser();			
	}
	
	public function isLogged(){
		if ($this->getUser()) return true;
		else return false;		
	}
	
	public function getUser() {
		return $this->user;
	}
	private function unsetUser() {
		unset($this->user);
		$this->save();
	}
	private function setUser(User $user) {
		$this->user = $user;
		$this->save();
	}
	
	public function save() {
		$this->notifyObservers();			
	}
	
// For better tests	
	private function getUserTable() {
		if (!$this->userTable) $this->userTable = Doctrine::getTable('User');
		return $this->userTable;
	}
	public function setUserTable(Injectable $userTable) {
		$this->userTable = $userTable;
	}
	public function unsetUserTable() {
		unset($this->userTable);
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