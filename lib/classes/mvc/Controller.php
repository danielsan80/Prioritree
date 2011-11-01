<?php
	class Controller {
		protected static $instance = null;		
		protected $dispatcher;
		protected $view;

		public function __construct(View $view) {
			$this->view = $view;
		}
		
		public static function getInstance(View $view) {
			if(self::$instance == null) {
				$c = __CLASS__;
				self::$instance = new $c($view);
			}
			return self::$instance;
		}

		public function dispatch(Dispatcher $dispatcher, $layout = 'layout', $print = true) {
			$this->dispatcher = $dispatcher;
			$data = $dispatcher->getData();
			if (!is_array($data)) $data = array('main' => $data);

			$flashMsg = FlashMsg::getInstance();
			$msgBoxView = new View('msgBox');
			$msgBoxView->setData(array('msgs' => $flashMsg->getMsgs()));
			$flashMsg->clrMsgs();
			$msgBoxView->setTpl('msgBox.tpl.php');
			$data['msgBox'] = $msgBoxView->getContent();
			
			$this->view->setData($data);
			$content = $this->view->getContent($layout.'.tpl.php');
			if ($print) echo $content;
		}		
	}