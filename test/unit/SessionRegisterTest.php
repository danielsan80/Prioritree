<?php
define('ROOT', '../');
require_once(ROOT.'test/inc/initialization.inc.php');

class SessionRegisterTest extends PHPUnit_Framework_TestCase {
	
	protected function getMock_Observable($property,$value) {
		$observable = $this->getMock('Observable', array(
			'registerObserver','removeObserver','notifyObservers','registerObservableProperty',
			'removeObservableProperty', 'getObservableProperties', 'getProperty'
		));
		$observable->expects($this->any())
			->method('getObservableProperties')
			->will($this->returnValue(array($property)));
		$observable->expects($this->any())
			->method('getProperty')
			->will($this->returnValue($value));

		return $observable;		
	}

	protected function setUp() {
	}

	protected function tearDown() {}
	
	public function testUpdate() {  
		$observable1 = $this->getMock_Observable('property','value1');
		$observable2 = $this->getMock_Observable('property','value2');
				
		$sessionRegister = new SessionRegister($observable1,'mySessionVar1');  	
		$sessionRegister->update();
		$this->assertEquals($sessionRegister->get('property'),'value1');
		$sessionRegister->observe($observable2);
		$this->assertEquals($sessionRegister->get('property'),'value1');
		$sessionRegister->update();
		$this->assertEquals($sessionRegister->get('property'),'value2');
	}

	public function testUpdateSessionSeparation() {  
		$observable1 = $this->getMock_Observable('property','value1');
		$observable2 = $this->getMock_Observable('property','value2');
				
		$sessionRegister1 = new SessionRegister($observable1,'mySessionVar1');  	
		$sessionRegister2 = new SessionRegister($observable2,'mySessionVar2');  	
		$sessionRegister1->update();
		$this->assertEquals($sessionRegister1->get('property'),'value1');
		$sessionRegister2->update();
		$this->assertEquals($sessionRegister2->get('property'),'value2');
		$this->assertEquals($sessionRegister1->get('property'),'value1');
	}

	public function testUpdateSessionCondivision() {  
		$observable1 = $this->getMock_Observable('property','value1');
		$observable2 = $this->getMock_Observable('property','value2');
				
		$sessionRegister1 = new SessionRegister($observable1);  	
		$sessionRegister2 = new SessionRegister($observable2);  	
		$sessionRegister1->update();
		$this->assertEquals($sessionRegister1->get('property'),'value1');
		$sessionRegister2->update();
		$this->assertEquals($sessionRegister2->get('property'),'value2');
		$this->assertEquals($sessionRegister1->get('property'),'value2');
	}

}
