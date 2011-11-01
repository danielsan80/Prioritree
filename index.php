<?php
	require_once 'inc/initialization.inc.php';
	$file = 'mytasks.yml';
	$builder = new TaskBuilder();
	$root = $builder->loadFromFile($file);
	file_put_contents($file, $root->getAsYaml());	
	