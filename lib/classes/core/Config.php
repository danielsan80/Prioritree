<?php
class Config {
	static public function get($key, $context='general') {
		if ($_SESSION['config'] && $_SESSION['config']['general']['production']) $config = $_SESSION['config'];
		else {
			$config = parse_ini_file(self::getROOT().'data/config.ini', true);
			$_SESSION['config'] = $config;
		}
		if (isset($config[$context][$key])) 	$out = trim($config[$context][$key]);
		else 									$out = '{{'.($context!='general'?$context.'|':'').$key.'}}';
		
		if ( (string)$out === (string)(int)$out )		return (int)$out;
		if ( (string)$out === (string)(double)$out )	return (double)$out;
		
		return $out;
	}
	
	static public function getROOT() {
		return ROOT;
	}
}