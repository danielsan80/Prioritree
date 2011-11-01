<?php
	class Actions {
		protected function getUser() {
			$auth = Auth::getInstance();
			return $auth->isLogged();			
		}
		
		protected function forward($url) {
			//@todo se il modulo è lo stesso chiama direttamente la funzione senza fare un'altra chiamata http
			header('Location: '.Util::url_for($url));
			exit();			
		}
		
		protected function forward404() {
			header('HTTP/1.0 404 Not Found');
			exit();
		}
	}