<?php

namespace Dan\Prioritree\Tests\Model;

use Dan\Prioritree\Test\WebTestCase;
use Dan\Prioritree\Model\Task;
use Dan\Prioritree\Model\TaskRoot;
use Dan\Prioritree\Model\TaskIterator;

class TaskIteratorTest extends WebTestCase {

	
	public function test_Traversing() {
		
		$root = new TaskRoot('Root');
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));
		$child2->add($child21 = new Task('child21'));
		$child2->add($child22 = new Task('child22'));
		
		$order = array($root,$child1,$child11,$child12,$child2,$child21,$child22);
		$order = array('Root','child1','child11','child12','child2','child21','child22');

		$iterator = new TaskIterator($root);
		
		$i=0;
		foreach($iterator as $key => $value) {
			$this->assertEquals($key, $i);			
			$this->assertEquals($value->getName(), $order[$i++]);			
		}
	}
}
