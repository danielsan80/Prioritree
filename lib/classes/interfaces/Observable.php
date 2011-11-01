<?php
	interface Observable {
		public function registerObserver(Observer $o);
		public function removeObserver(Observer $o);
		public function notifyObservers();
		public function registerObservableProperty($str);
		public function removeObservableProperty($str);
		public function getObservableProperties();
	}
?>