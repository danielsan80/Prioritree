<?php
class TestInitializationDoctrine extends InitializationDoctrine {
	
	public function __construct(){
		$this->createDatabase(
			Config::get('server','database'),
			Config::get('username','database'),
			Config::get('password','database'),
			Config::get('database','database').'_test'
		);	
		parent::__construct();
		
		//Doctrine_Core::dropDatabases();
		//Doctrine_Core::createDatabases();	
		//Doctrine_Core::createTablesFromModels(ROOT.'lib/classes/models');
		//Doctrine_Core::loadData(ROOT.'test/data/fixtures/data.yml');
	}
	
	protected function setConnectionParameters() {
		parent::setConnectionParameters();
		//$this->username = getConfig('username','databaseTest');
		//$this->password = getConfig('password','databaseTest');
		//$this->server 	= getConfig('server','databaseTest');
		$this->database .= '_test';		
	}	
	
	private function createDatabase($server, $username, $password, $database) {
		$conn = mysql_connect($server,$username,$password);
		mysql_select_db($database);
		$sql = "CREATE DATABASE IF NOT EXISTS ".$database;
			if (!mysql_query($sql)) throw new Exception('Creating database('.$database.') error: '.mysql_error());
		mysql_close($conn);
	}
}