<?php
class InitializationDoctrine {
	protected $username;
	protected $password;
	protected $server;
	protected $database;	
	
	public function __construct() {
		$this->setConnectionParameters();
		$manager = Doctrine_Manager::getInstance();	
		$manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);
		Doctrine_Manager::connection(
			'mysql://'.
			($this->username).
			($this->password?':'.$this->password:'').
			'@'.
			($this->server).
			'/'.
			($this->database),
			'doctrine'
		);
	}
	
	protected function setConnectionParameters() {
		$this->username = Config::get('username', 'database');
		$this->password = Config::get('password', 'database');
		$this->server 	= Config::get('server', 'database');
		$this->database = Config::get('database', 'database');		
	}
		
}