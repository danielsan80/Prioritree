<?php
	require_once(dirname(__FILE__).'/defineROOT.inc.php');	
	if(!session_id()) session_start();	
	error_reporting(E_ALL ^ E_NOTICE);

//FUNCTION
//	require_once(ROOT.'inc/functions.php');	
//	if (getConfig('display_errors'))	ini_set('display_errors', true);
//	else								ini_set('display_errors', false);	
	
//CLASSES
	require_once(ROOT.'lib/autoloader/Autoloader.php');	
	//require_once(ROOT.'lib/Doctrine/bootstrap.php');
	//require_once(ROOT.'lib/Twig/Autoloader.php');
	//Twig_Autoloader::register();
	//require_once ROOT.'lib/tonic/lib/tonic.php';
	

//INITIALIZATION	
	//new InitializationDoctrine();