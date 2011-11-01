<?php
define('ROOT', '../');
require_once(ROOT.'test/inc/initialization.inc.php');

class AuthTest extends PHPUnit_Framework_TestCase {
	protected $auth;
	protected $rightUser = array('pallino@gmail.com','password');
	protected $wrongUser = array('wrongEmail@gmail.com', 'wrongPassword');
	
	protected function setUp() {
		$this->auth = Auth::getInstance();		
	}
	protected function tearDown(){
		$this->auth->unsetUserTable();
	}
	
	protected function getMock_UserTable_ExpectLoginOk($user){
		list($email, $password) = $this->rightUser;
		$userTable = $this->getMock('Injectable', array('getUserByAccessData'));
		//$user = $this->getMock('User');
		$userTable->expects($this->any())
			->method('getUserByAccessData')
			->with($this->equalTo($email),$this->equalTo($password))
			->will($this->returnValue($user));
		return $userTable; 
	}
	protected function getMock_UserTable_ExpectLoginNotOk(){
		list($email, $password) = $this->wrongUser;
		$userTable = $this->getMock('Injectable', array('getUserByAccessData'));
		$userTable->expects($this->any())
			->method('getUserByAccessData')
			->with($this->equalTo($email),$this->equalTo($password))
			->will($this->returnValue(false));
		return $userTable;
	}
	
	public function testGetInstance(){
	   $auth1 = Auth::getInstance(); 
	   $auth2 = Auth::getInstance();
	   $this->assertTrue($auth1===$auth2);
	}

	public function testLogin_WrongAccessData(){
		list($email, $password) = $this->wrongUser;
		$userTable = $this->getMock_UserTable_ExpectLoginNotOk();
		$this->auth->setUserTable($userTable);
		
		$this->auth->logout();
		$this->assertFalse($this->auth->login($email, $password));
		$this->assertNull($this->auth->getUser());
	}	 

	public function testLogin_RightAccessData(){
		list($email, $password) = $this->rightUser;
		$user = $this->getMock('User');
		$userTable = $this->getMock_UserTable_ExpectLoginOk($user);
		$this->auth->setUserTable($userTable);
		
		$this->assertTrue($this->auth->login($email, $password));
		$this->assertEquals($this->auth->getUser(), $user);
	}
	
	public function testLogin_WrongDataAccessWhenUserIsLoggedYet(){
		list($email, $password) = $this->rightUser;
		$user = $this->getMock('User');
		$userTable = $this->getMock_UserTable_ExpectLoginOk($user);
		$this->auth->setUserTable($userTable);
		
		$this->assertTrue($this->auth->login($email, $password));
		
		list($email, $password) = $this->wrongUser;
		$userTable = $this->getMock_UserTable_ExpectLoginNotOk();
		$this->auth->setUserTable($userTable);
		
		$this->assertFalse($this->auth->login($email, $password));
		$this->assertEquals($this->auth->getUser(), $user);
	}

	public function testLogout(){
	 	list($email, $password) = $this->rightUser;
		$userTable = $this->getMock_UserTable_ExpectLoginOk($user);
		$this->auth->setUserTable($userTable);
		$this->auth->login($email, $password);
		$this->assertTrue($this->auth->getUser() instanceof User);
		$this->auth->logout();
		$this->assertNull($this->auth->getUser());			
	}

	/**
	 * @todo Implement testIsLogged().
	 */
	public function testIsLogged(){
		list($email, $password) = $this->rightUser;
		$user = $this->getMock('User');
		$userTable = $this->getMock_UserTable_ExpectLoginOk($user);
		$this->auth->setUserTable($userTable);
		
		$this->assertTrue($this->auth->login($email, $password));
		$this->assertTrue($this->auth->isLogged());
		
		$this->auth->logout();
		$this->assertFalse($this->auth->isLogged());
	}
}
