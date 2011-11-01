<?php
	require_once(dirname(__FILE__).'/defineROOT.inc.php');	
	if(!session_id()) session_start();	
	error_reporting(E_ALL ^ E_NOTICE);

//FUNCTION
//	require_once(ROOT.'inc/functions.php');	
//	if (getConfig('display_errors'))	ini_set('display_errors', true);
//	else								ini_set('display_errors', false);	
	
//CLASSES
	require_once 'PHPUnit/Framework.php';
	require_once(ROOT.'lib/autoloader/Autoloader.php');
	require_once(ROOT.'lib/Doctrine/bootstrap.php');

//INITIALIZATION
		
	//new InitializationDoctrine();
	//new TestInitializationDoctrine();