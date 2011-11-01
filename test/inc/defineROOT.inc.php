<?php
if (!defined('ROOT')) {
	$root = ''; $i=0;
	while (($i++<30) && !file_exists($root.'ROOT'))	$root .= '../';
	if (!file_exists($root.'ROOT')) trigger_error('ROOT not found!', E_USER_ERROR);
	define('ROOT', $root);		
}