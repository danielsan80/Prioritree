<?php
define('ROOT', '../');
require_once(ROOT.'test/inc/initialization.inc.php');

class FlashMsgTest extends PHPUnit_Framework_TestCase {
	
	protected $flashMsg;

	protected function setUp() {
		$this->flashMsg = FlashMsg::getInstance();
	}
	protected function tearDown() {
	}

	public function testGetInstance(){
	   $obj1 = FlashMsg::getInstance(); 
	   $obj2 = FlashMsg::getInstance();
	   $this->assertTrue($obj1===$obj2);
	}

	public function testAddMsg() {
		$this->flashMsg->addMsg('Error Message', 'error');
		$this->flashMsg->addMsg('Notice Message','notice');
		$msgs = $this->flashMsg->getMsgs();
		$expectedMsgs=array(
			'error'=>array('Error Message'),
			'notice'=>array('Notice Message')
			);
		$this->assertEquals($msgs,$expectedMsgs);
	}
}
?>
