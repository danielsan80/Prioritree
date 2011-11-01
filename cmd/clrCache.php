<?php
require_once '../inc/initialization.inc.php';
$cacheDir = Config::getRoot().'cache/';
if (!file_exists($cacheDir)) throw new Exception('Cache not found');

$paths = glob($cacheDir.'*');
echo '<pre>';
while ( $path = array_shift($paths)) {
	echo $path.' ';
	if (is_dir($path)) {
		if ($files = glob($path.'/*')) {
			echo "exploded\n";
			$paths = $files + $paths;
			$paths[] = $path;
			continue;
		} else {
			rmdir( $path );
		}	
	} else {
		unlink($path);	
	}
	echo "removed\n";
	if ($i++>100) break;
}
echo '</pre>';
