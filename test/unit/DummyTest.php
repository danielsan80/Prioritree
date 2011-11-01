<?php
require_once(dirname(__FILE__).'/../inc/initialization.inc.php');

class DummyTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {}
	protected function tearDown(){}
	
	public function testMock() {
		$mock = $this->getMock('Prova', array('get','set'));
		$mock->expects($this->any())
			->method('get')
			->with($this->equalTo('key'))
			->will($this->returnValue('value'));
		$mock->expects($this->any())
			->method('set')
			->with($this->equalTo('key'),$this->equalTo('value'))
			->will($this->returnValue(true));
		
		$this->assertEquals($mock->set('key', 'value'), true);
		$this->assertEquals($mock->get('key'), 'value');
	}
}
