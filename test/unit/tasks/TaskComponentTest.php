<?php
require_once(dirname(__FILE__).'/../../inc/initialization.inc.php');

class TaskComponentTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {}
	protected function tearDown(){}
	
	public function testAdd() {
		$obj = new MyTask('componentName');
		try {
			$obj->add(new MyTask('componentNameChild'));
			$this->fail();
		} catch (TaskException $e) {
			$this->assertEquals($e->getMessage(), 'Unsupported operation');
		}
	}
	public function testRemove() {
		$obj = new MyTask('componentName');
		try {
			$obj->remove(new MyTask('componentNameChile'));
			$this->fail();
		} catch (TaskException $e) {
			$this->assertEquals($e->getMessage(), 'Unsupported operation');
		}
	}
	public function testGetChild() {
		$obj = new MyTask('componentName');
		try {
			$obj->getChild(0);
			$this->fail();
		} catch (TaskException $e) {
			$this->assertEquals($e->getMessage(), 'Unsupported operation');
		}
	}
}
