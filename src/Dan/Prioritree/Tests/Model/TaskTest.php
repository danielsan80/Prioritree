<?php

namespace Dan\Prioritree\Tests\Model;

use Dan\Prioritree\Test\WebTestCase;
use Dan\Prioritree\Test\Model\MyTask;
use Dan\Prioritree\Model\Task;
use Dan\Prioritree\Model\TaskRoot;
use Symfony\Component\Yaml\Yaml;

class TaskTest extends WebTestCase {

	public function test_children_management() {
		$task = new Task('myTask');
		$c = new MyTask('child1');
		$task->add($c);
		$this->assertEquals($c, $task->getChild(0));
		$this->assertEquals(1, $task->countChildren());
		$task->remove($c);
		$this->assertNull($task->getChild(0));
	}
	
	public function test_getAssignedTime() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(7);
		$child2->setPriority(3);
		
		$this->assertEquals(70,$child1->getAssignedTime());
		$this->assertEquals(30,$child2->getAssignedTime());
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);

		$this->assertEquals(35,$child11->getAssignedTime());
		$this->assertEquals(35,$child12->getAssignedTime());
		
	}

	public function test_prioritySum_zero() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(0);
		$child2->setPriority(0);
		
		$this->assertEquals(50,$child1->getAssignedTime());
		$this->assertEquals(50,$child2->getAssignedTime());
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);

		$this->assertEquals(25,$child11->getAssignedTime());
		$this->assertEquals(25,$child12->getAssignedTime());
		
	}

	public function test_a_priority_is_zero() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(1);
		$child2->setPriority(0);
		
		$this->assertEquals(100,$child1->getAssignedTime());
		$this->assertEquals(0,$child2->getAssignedTime());

		$this->assertEquals(0,$child2->getProgress());
	}

	public function test_getNormalizedAssignedTime() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(500);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(7);
		$child2->setPriority(3);
		
		$this->assertEquals(0.70,$child1->getNormalizedAssignedTime());
		$this->assertEquals(0.30,$child2->getNormalizedAssignedTime());
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);

		$this->assertEquals(0.35,$child11->getNormalizedAssignedTime());
		$this->assertEquals(0.35,$child12->getNormalizedAssignedTime());
		
	}


	public function test_getRealUsedTime() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(7);
		$child2->setPriority(3);
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);
		
		$root->incUsedTime(4);
			$child1->incUsedTime(4);
				$child11->incUsedTime(1);
				$child12->incUsedTime(1);
				
		$this->assertEquals(4,$root->getUsedTime());
		$this->assertEquals(4,$child1->getUsedTime());
		$this->assertEquals(1,$child12->getUsedTime());
		
		$this->assertEquals(6,$root->getDescendantsUsedTime());
		$this->assertEquals(2,$child1->getDescendantsUsedTime());
		$this->assertEquals(0,$child12->getDescendantsUsedTime());

		$this->assertEquals(0,$root->getAncestorsUsedTime());
		$this->assertEquals(2,$child1->getAncestorsUsedTime());
		$this->assertEquals(3,$child12->getAncestorsUsedTime());

		$this->assertEquals(10,$root->getRealUsedTime());
		$this->assertEquals(8,$child1->getRealUsedTime());
		$this->assertEquals(4,$child12->getRealUsedTime());		
	}
	
	public function test_getProgress() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(7);
		$child2->setPriority(3);
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);
		
		$root->incUsedTime(4);
			$child1->incUsedTime(4);
				$child11->incUsedTime(1);
				$child12->incUsedTime(1);

		$this->assertEquals(10/100,$root->getProgress());
		$this->assertEquals(8/70,$child1->getProgress());
		$this->assertEquals(4/35,$child12->getProgress());		
	}
	public function test_resetUsedTime() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(5);
		$child2->setPriority(5);
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);

		$child2->add($child21 = new Task('child21'));
		$child2->add($child22 = new Task('child22'));

		
		$root->incUsedTime(50);
			$child1->incUsedTime(20);
				$child11->incUsedTime(2);
				$child12->incUsedTime(2);
			$child2->incUsedTime(20);
				$child21->incUsedTime(2);
				$child22->incUsedTime(2);
	
		$this->assertEquals(98,$root->getRealUsedTime());
		$this->assertEquals(49,$child1->getRealUsedTime());
		$this->assertEquals(24.5,$child11->getRealUsedTime());
		$this->assertEquals(24.5,$child12->getRealUsedTime());
		$this->assertEquals(49,$child2->getRealUsedTime());
		$this->assertEquals(24.5,$child21->getRealUsedTime());
		$this->assertEquals(24.5,$child22->getRealUsedTime());

		$root->resetUsedTime();

		$this->assertEquals(-2,$root->getRealUsedTime());
		$this->assertEquals(-1,$child1->getRealUsedTime());
		$this->assertEquals(-0.5,$child11->getRealUsedTime());
		$this->assertEquals(-0.5,$child12->getRealUsedTime());
		$this->assertEquals(-1,$child2->getRealUsedTime());
		$this->assertEquals(-0.5,$child21->getRealUsedTime());
		$this->assertEquals(-0.5,$child22->getRealUsedTime());
			
	}
	
	public function test_resetUsedTime_moreComplex() {
		$root = new TaskRoot('Root');
		$root->setTimeBox(100);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(7);
		$child2->setPriority(3);
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));

		$child11->setPriority(5);
		$child12->setPriority(5);

		$child2->add($child21 = new Task('child21'));
		$child2->add($child22 = new Task('child22'));

		$child21->setPriority(5);
		$child22->setPriority(5);

		$root->incUsedTime(50);
			$child1->incUsedTime(21);
				$child11->incUsedTime(2);
				$child12->incUsedTime(2);
			$child2->incUsedTime(9);
				$child21->incUsedTime(10);
				$child22->incUsedTime(2);
	
		$root->resetUsedTime();

		$this->assertEquals(-4,$root->getRealUsedTime());
		$this->assertEquals(-20,$child1->getRealUsedTime());
		$this->assertEquals(-10,$child11->getRealUsedTime());
		$this->assertEquals(-10,$child12->getRealUsedTime());
		$this->assertEquals(16,$child2->getRealUsedTime());
		$this->assertEquals(12,$child21->getRealUsedTime());
		$this->assertEquals(4,$child22->getRealUsedTime());
			
	}
	
	public function test_TaskRoot_getAsYaml_empty() {
		$root = new TaskRoot('root');
		$root->setTimeBox(100);		
		$root->incUsedTime(50);
		
		//echo "\n".$root->getAsYaml();
		$expected = Yaml::parse(file_get_contents(__DIR__.'/../fixtures/tasks/TaskRoot_empty.yml'));		
		$this->assertEquals($expected,Yaml::parse($root->getAsYaml()));		
	}

	public function test_TaskRoot_getAsYaml_notEmpty() {
		$root = new TaskRoot('root');
		$root->setTimeBox(100);		
		$root->incUsedTime(50);
		
		$root->add($child1 = new Task('child1'));
		$root->add($child2 = new Task('child2'));
		
		$child1->setPriority(7);
		$child2->setPriority(3);
		
		$child1->add($child11 = new Task('child11'));
		$child1->add($child12 = new Task('child12'));
		
		$child11->setPriority(5);
		$child12->setPriority(5);

		$child2->add($child21 = new Task('child21'));
		$child2->add($child22 = new Task('child22'));

		$child21->setPriority(5);
		$child22->setPriority(5);
		$child22->incUsedTime(5);
		
		//echo "\n".$root->getAsYaml();
		$expected = Yaml::parse(file_get_contents(__DIR__.'/../fixtures/tasks/TaskRoot_notEmpty.yml'));
		$this->assertEquals($expected,Yaml::parse($root->getAsYaml()));		
	}

	
}