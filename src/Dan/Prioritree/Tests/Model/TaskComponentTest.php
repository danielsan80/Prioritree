<?php

namespace Dan\Prioritree\Tests\Model;

use Dan\Prioritree\Test\WebTestCase;
use Dan\Prioritree\Model\TaskException;
use Dan\Prioritree\Model\TaskComponent;
use Dan\Prioritree\Test\Model\MyTask;

class TaskComponentTest extends WebTestCase {
	
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
