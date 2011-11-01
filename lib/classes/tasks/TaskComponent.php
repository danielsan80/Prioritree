<?php
abstract class TaskComponent {
	protected $parent;
	protected $priority;
	
	public function add(TaskComponent $task) {
		throw new TaskException('Unsupported operation');
	}
	public function remove(TaskComponent $task) {
		throw new TaskException('Unsupported operation');
	}
	public function getChild($i) {
		throw new TaskException('Unsupported operation');
	}
	
	public function setParent(Task $task) { $this->parent = $task; }
	public function getParent() { return $this->parent; }
	
}