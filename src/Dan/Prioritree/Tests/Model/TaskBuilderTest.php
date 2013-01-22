<?php

namespace Dan\Prioritree\Tests\Model;

use Dan\Prioritree\Test\WebTestCase;
use Dan\Prioritree\Model\TaskBuilder;
use Dan\Prioritree\Model\TaskIterator;


class TaskBuilderTest extends WebTestCase {

	public function test_loading_yaml_file() {
		$builder = new TaskBuilder();
		$root = $builder->loadFromFile(__DIR__.'/../fixtures/tasks/TaskRoot_notEmpty.yml');
		
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