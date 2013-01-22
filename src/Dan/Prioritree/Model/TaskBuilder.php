<?php
namespace Dan\Prioritree\Model;

use Symfony\Component\Yaml\Yaml;

class TaskBuilder {
	
	public function loadFromFile($file) {
		$root = new TaskRoot();
		$yaml = file_get_contents($file);
		$record = Yaml::parse($yaml);

		$this->loadTaskRootFromRecord($root, $record);
		return $root;
	}
	
	private function loadTaskRootFromRecord(TaskRoot $root, $record) {
		foreach($record as $name => $record) break;
		$data = $this->getDataAsArray($record['data']);
		$root->setTimebox($data['timeBox']);
		$root->incUsedTime($data['usedTime']);
                
		$this->loadChildrenFromRecord($root, $record['children']);
	}

	private function loadTaskFromRecord(Task $task, $record) {
		if (!$record) return;
		$data = $this->getDataAsArray($record['data']);
		$task->setPriority($data['priority']);
		$task->incUsedTime($data['usedTime']);
		$this->loadChildrenFromRecord($task, isset($record['children'])?$record['children']:null);
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