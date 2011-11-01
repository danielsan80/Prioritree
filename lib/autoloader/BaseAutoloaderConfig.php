<?php
	abstract class BaseAutoloaderConfig {
		protected $root;
		protected $cachefile;
		protected $paths = array();
		protected $classFilenameMethods = array('default');
		
		public function __construct(){ $this->configure(); }

		abstract function configure();
		
		public function addPath($path, $recursive=true, $method=null ) {
			$this->paths[] = array('path' => $path, 'recursive' => $recursive, 'method' => $method);
		}
		public function addClassFilenameMethod($method) {
			$this->classFilenameMethods[] = $method;
		}
		
		public function getPaths($class) {
			$paths = $this->paths;
			foreach($paths as $i => $path){
				if ($path['method'])
					$path['filenames'] = array($this->getClassFilename($class,$path['method']));
				else 
					$path['filenames'] = $this->getClassFilenames($class);
				unset($path['method']);
				$paths[$i] = $path;
			}
			return $paths;
		}

		
		public function getRoot($default='') { return $this->root?$this->root:$default; }
		public function setRoot($root='') { $this->root = $root; }

		public function getCachefile($default='') { return $this->cachefile?$this->cachefile:$default; }
		public function setCachefile($cachefile) {
			$this->cachefile = $cachefile;
			if (file_exists(ROOT.$cachefile)){
				touch(ROOT.$cachefile);
				return;
			}
			$parts = explode('/',$cachefile);
			$n = count($parts)-1;
			$path = ROOT;
			for($i=0; $i<$n; $i++) {
				$path .= $parts[$i].'/';
				if(file_exists($path) && is_dir($path)) continue;
				mkdir($path, 0777);
			}
			touch(ROOT.$cachefile);
		}
		
		private function getClassFilenameMethods() {
			return $this->classFilenameMethods;
		}
		
		private function getClassFilename($class, $method) {
			$method = 'getClassFilename_'.$method;
			if (!method_exists($this, $method)) throw new Exception($method.' is not defined in BaseAutoloaderConfig');
			return $this->$method($class);
		}
		
		private function getClassFilename_default($class){	
			return $class.'.php';
		}

		private function getClassFilenames($class) {
			$methods = $this->getClassFilenameMethods();
			$filenames = array();
			foreach($methods as $method) {
				$filenames[] = $this->getClassFilename($class, $method);
			}
			return $filenames;
		}
	}
