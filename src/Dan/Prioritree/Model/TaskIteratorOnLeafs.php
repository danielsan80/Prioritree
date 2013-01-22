<?php
namespace Dan\Prioritree\Model;


class FormIteratorOnFields implements Iterator {
	protected $iterator;
	protected $position;
	
	public function __construct(Form $form) {
		$this->iterator = new FormIterator($form);
	}
		
	//Iterator Interface
	
	private function nextField() {
		$current = $this->iterator->current();
		while($this->iterator->valid() && $current->isComposite()) {
			$this->iterator->next();
			$current = $this->iterator->current();
		}
	}
	
	public function current() {
		return $this->iterator->current();
	}
	public function key() {
		return $this->position;
	}
	public function next() {		
		$this->iterator->next();
		$this->nextField();
		$this->position++;
	}
	
	public function rewind() {
		$this->position = 0;
		$this->iterator->rewind();
		$this->nextField();
	}
	
	public function valid() {
		return $this->iterator->valid();		
	}
}