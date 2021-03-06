<?php
namespace Dan\Prioritree\Model;

class Task extends TaskComponent{
	protected $children=array();
	protected $usedTime = 0;
	protected $name;
	protected $priority;
	
	public function __construct($name){
		$this->name = $name;
	}
	
	public function getName($default='noName') {return $this->name;}
	public function setName($name) {$this->name = $name;}
	
//Composite
	public function add(TaskComponent $task) {
		$task->setParent($this);
		$this->children[] = $task;
		return count($this->children)-1;		
	}
	public function remove(TaskComponent $task) {
		$i = array_search($task, $this->children);
		if ( $i!==false ) {
			unset($this->children[$i]);
			$i++;
			while(isset($this->children[$i]) && $this->children[$i]) {
				$this->children[$i-1] = $this->children[$i];
				unset($this->children[$i]);
			}		
		}
	}
	public function getChild($i) {
		return isset($this->children[$i])?$this->children[$i]:null;
	}
        
	public function isEqual(Task $task) {
            if ($this->getName() != $task->getName()) {
                return false;
            }
            if ($this->getPriority() != $task->getPriority()) {
                return false;
            }
            if ($this->getUsedTime() != $task->getUsedTime()) {
                return false;
            }
            
            if (($n = $this->countChildren()) != $task->countChildren()) {
                return false;
            }
            for ($i=0; $i<$n; $i++) {
                if (!$this->getChild($i)->isEqual($task->getChild($i))) {
                    return false;
                }
            }
            
            
            return true;
	}
	
	public function countChildren(){
		return count($this->children);
	}
	
//Assigned
	public function getAssignedTime(){
		$parent = $this->getParent();
		$time = $parent->getAssignedTime();
		return $time * $this->getNormalizedPriority();
	}

	public function getNormalizedAssignedTime(){
		$parent = $this->getParent();
		$assignedTime = $parent->getNormalizedAssignedTime();
		return $assignedTime * $this->getNormalizedPriority();
	}
	
// Priority	

	public function setPriority($value) {
		$this->priority = $value;
	}
	public function getPriority($default=6) {
		if(is_null($this->priority)) return $default;
		return $this->priority;
	}
	
	public function getChildrenPrioritySum(){
		$sum = 0;
		foreach($this->children as $i => $child) {
			$sum += $child->getPriority();	
		}
		return $sum;
	}
	public function getNormalizedPriority(){
		$parent = $this->getParent();
		$prioritySum = $parent->getChildrenPrioritySum();
		$priority = $this->getPriority();
		if (!$prioritySum)
			return 1/$parent->countChildren();
		return ($priority/$prioritySum);
	}
	
	public function getProgress() {
		if (!($assignedTime = $this->getAssignedTime())) return 0;
		return $this->getRealUsedTime()/$assignedTime;
	}
	
// UsedTime	
	public function incUsedTime($time){
		$this->usedTime += $time;
	}	
	public function getUsedTime(){
		return $this->usedTime;
	}
	
	public function getDescendantsUsedTime(){
		$sum = 0;
		foreach($this->children as $i => $child) {
			$sum += $child->getUsedTime();	
			$sum += $child->getDescendantsUsedTime();	
		}
		return $sum;	
	}
	public function getAncestorsUsedTime(){
		$parent = $this->getParent();
		$sum = 0;
		$sum += $parent->getUsedTime()/$parent->countChildren();
		$sum += $parent->getAncestorsUsedTime()/$parent->countChildren();		
		return $sum;
	}

	public function getRealUsedTime(){
		return $this->getAncestorsUsedTime()+$this->getUsedTime()+$this->getDescendantsUsedTime();
	}
	
	public function resetUsedTime(){
		if (!$this->countChildren()){
			$this->usedTime = $this->getRealUsedTime()-$this->getAssignedTime();
		}else {
			foreach($this->children as $child)
				$child->resetUsedTime();
			$this->usedTime = 0;
		}
			
	}	
	
	public function getChildrenAsArray(){
		$children = array();
		foreach($this->children as $child){
			$child = $child->getAsArray();
			$children[$child['name']] = $child;
			unset($children[$child['name']]['name']);
		}
		return $children;
	}

	public function getAsArray(){
		$progress = round($this->getProgress()*100,2);
		$realUsedTime = round($this->getRealUsedTime(),2);
		$assignedTime = round($this->getAssignedTime(),2);
		
		$data = array(
			'name' => $this->name,
			'data' =>
				'usedTime='.$this->getUsedTime().'; '.
				'priority='.$this->getPriority().'; '.
				'progress='.$progress.'%; '.
				'time='.$realUsedTime.'/'.$assignedTime
                                //.'; '.'pomodori='.str_pad('',round($assignedTime) - round($realUsedTime),'o')
		);
		if ($children = $this->getChildrenAsArray()) $data['children'] = $children;
		return $data;
	}
	
	public function loadChildrenFromArray($array){
		if ($children = $array){
			foreach($children as $name => $record){
				$this->add($child = new Task($name));
				$child->loadFromArray($record);
			}
		}
	}
	
}