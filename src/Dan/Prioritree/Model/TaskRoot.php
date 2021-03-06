<?php
namespace Dan\Prioritree\Model;

use Symfony\Component\Yaml\Yaml;

class TaskRoot extends Task{
    private $timeBox;


    public function __construct($name='Root') {
        parent::__construct($name);
    }
    public function setTimeBox($time){
        $this->timeBox = $time;
    }
    public function getTimeBox(){
            return $this->timeBox;
    }

    public function isEqual(TaskRoot $root) {
        if ($this->getTimeBox() != $root->getTimeBox()) {
            return false;
        }
        return parent::isEqual($root);
    }

    public function getAssignedTime(){
        return $this->timeBox;
    }

    public function getNormalizedAssignedTime(){
        return 1;
    }

    public function getRealUsedTime(){
        return $this->getUsedTime()+$this->getDescendantsUsedTime();
    }

    public function getAncestorsUsedTime(){
        return 0;
    }

    public function getAsArray(){
        $progress = round($this->getProgress()*100,2);
        $realUsedTime = round($this->getRealUsedTime(),2);
        $assignedTime = round($this->getAssignedTime(),2);			
        $data = array(
                'data' =>
                        'usedTime='.$this->getUsedTime().'; '.
                        'timeBox='.$this->getAssignedTime().'; '.
                        'progress='.$progress.'%; '.
                        'time='.$realUsedTime.'/'.$assignedTime
                        //.'; '.'pomodori='.str_pad('',round($assignedTime) - round($realUsedTime),'o')
        );
        if ($children = $this->getChildrenAsArray()) $data['children'] = $children;
        return array($this->getName() => $data);		
    }

    public function getAsYaml(){
        $yaml = Yaml::dump($this->getAsArray(),9);
        $yaml = str_replace("'",'', $yaml);
        return $yaml;
    }
}
