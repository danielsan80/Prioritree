<?php
namespace Dan\Prioritree\Model;

class TaskIterator implements \Iterator {
	protected $position;
	protected $root;
	protected $indexes;
	protected $tasks;
	
	public function __construct(Task $task) {
		$this->root = $task;
	}
	
	//Iterator Interface
	
	public function current() {
		if ($this->tasks) return $this->tasks[0];
		else return null;
	}
	public function key() {
		return $this->position;
	}
	public function next() {
		while ($this->tasks){
			$task = $this->tasks[0];
			$index = $this->indexes[0];
			if ($task->countChildren() && $child = $task->getChild($index)){
				array_unshift($this->tasks, $child);
				array_unshift($this->indexes, 0);
				break;
			} else {
				array_shift($this->tasks);
				array_shift($this->indexes);
                                
				if (!isset($this->indexes[0])) {
                                    $this->indexes[0]=0;
                                }
                                $this->indexes[0]++;
			}
		}
		$this->position++;
	}
	
	public function rewind() {
		$this->position = 0;
		$this->tasks = array();
		$this->indexes = array();
		array_unshift($this->tasks, $this->root);
		array_unshift($this->indexes, 0);
	}
	
	public function valid() {
		if ($this->tasks) return true;
		else return false;		
	}
}