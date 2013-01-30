<?php
namespace Dan\Prioritree\Model;

use Symfony\Component\Yaml\Yaml;

class TaskBuilder {
	
	public function loadFromFile($file) {
		$yaml = file_get_contents($file);
                
                return $this->loadFromString($yaml);
	}
        
	public function loadFromString($str) {
                $root = new TaskRoot();
		$record = Yaml::parse($str);

		$this->loadTaskRootFromRecord($root, $record);
                
		return $root;
	}
	
	private function loadTaskRootFromRecord(TaskRoot $root, $record) {
		foreach($record as $name => $record) break;
		$data = $this->getDataAsArray($record['data']);
                $data = array_merge(array(
                    'timeBox' => 100,
                    'usedTime' => 0,
                ), $data);
		$root->setTimebox($data['timeBox']);
		$root->incUsedTime($data['usedTime']);
                
		$this->loadChildrenFromRecord($root, $record['children']);
	}

	private function loadTaskFromRecord(Task $task, $record) {
		if (!$record) return;
		$data = $this->getDataAsArray($record['data']);
                
                $data = array_merge(array(
                    'priority' => 0,
                    'usedTime' => 0,
                ), $data);
		$task->setPriority($data['priority']);
		$task->incUsedTime($data['usedTime']);
                
                $record['children'] = isset($record['children'])?$record['children']:null;
		$this->loadChildrenFromRecord($task, $record['children']);
	}
	
	private function loadChildrenFromRecord(Task $task, $children){
		if ($children){
			foreach($children as $name => $record){
				$task->add($child = new Task($name));
				$this->loadTaskFromRecord($child, $record);
			}
		}
	}
	
	private function getDataAsArray($data){
		$data = explode(';',$data);
		$out = array();
		foreach($data as $couple) {
			$couple = explode('=',$couple);
			$out[trim($couple[0])] = trim($couple[1]);
		}
		return $out;
	}	
	
}