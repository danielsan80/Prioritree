<?php
	if (!class_exists('BaseAutoladerConfig')) require_once(dirname(__FILE__).'/AutoloaderConfig.php');
	require_once(dirname(__FILE__).'/AutoloaderRegister.php');

	class Autoloader {
		
		private static $config=null;
		
		private static function isCommented($filename){
			$first = substr($filename,0,1);
			if ( $first=='.' || $first=='_' || $first=='!' ) return true;
			return false;
		}
		
		public static function defineClass($class){
			if (!self::$config) self::$config = new AutoloaderConfig();			
			$paths = self::$config->getPaths($class);
			$root = self::$config->getRoot();
			$register = AutoloaderRegister::getInstance($root.self::$config->getCachefile());
			
			if ($filename = $register->get($class)) {
				if (file_exists($root.$filename)){
					require_once $root.$filename;
					return;
				}
			}
			
			foreach($paths as $el) {
				$queue = array($el);	
				while ($el = array_shift($queue)) {
					if (!$dir = opendir($root.$el['path']))
						throw new Exception('Path '.$el['path'].' opening failed');
					foreach($el['filenames'] as $classFilename) {
						if (file_exists($root.$el['path'].$classFilename)) {
							require_once $root.$el['path'].$classFilename;
							$register->set($class,$el['path'].$classFilename);
							return;
						}
					}
					if (!$el['recursive']) continue;
					while($filename = readdir($dir)) {
						if ( self::isCommented($filename)  ) continue;
						if (is_dir($root.$el['path'].$filename)) {
							$_el = $el;
							$_el['path'] = $_el['path'].$filename.'/';
							$queue[] = $_el;
						}
					}
				}
			}	
		}
		
		public static function init(BaseAutoloaderConfig $config=null) {
			self::$config = $config;			
			spl_autoload_register(null, false);
			spl_autoload_extensions('.php');
			Autoloader::enable();
		}
		
		public static function enable() {
			spl_autoload_register(array('Autoloader','defineClass'));
		}
		public static function disable() {
			spl_autoload_unregister(array('Autoloader','defineClass'));
		}
		public static function clear() {
			self::disable();
			self::$config = null;
		}
	}
	Autoloader::init();
