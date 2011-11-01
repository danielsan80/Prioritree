<?php
require_once(dirname(__FILE__).'/../../inc/initialization.inc.php');

class TaskBuilderTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {}
	protected function tearDown(){}
	
	public function test_loading_yaml_file() {
		$builder = new TaskBuilder();
		$root = $builder->loadFromFile(ROOT.'test/data/tasks/TaskRoot_notEmpty.yml');
		
		$order = array('Root','child1','child11','child12','child2','child21','child22');

		$iterator = new TaskIterator($root);
		
		$i=0;
		foreach($iterator as $key => $value) {
			$this->assertEquals($key, $i);			
			$this->assertEquals($value->getName(), $order[$i++]);
		}
		$this->assertEquals(count($order), $i);
		//echo $root->getAsYaml();
		
	}
	
}