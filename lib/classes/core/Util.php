<?php
class Util {
	
	#Utilities
	static public function url_for($data) {
		return '?'.$data;
	}

	static public function isDotDir($dir) {
		return in_array( $dir,  array( '.', '..', '.svn' ) );
	}		

	static public function getAsArray($rs,$idField='') {
		if (mysql_num_rows($rs)==0) return array();
		mysql_data_seek($rs,0);
		$array = array();					
		while ($r = mysql_fetch_assoc($rs)) {
			if ($idField) $array[$r[$idField]] = $r;
			else $array[] = $r;
		}
		mysql_data_seek($rs,0);
		return $array;	
	}	
	
	static public function getOptionsFromArray($array, $selected='', $novalue=true) {
		$str='';
		if ($novalue) $str.='<option value=""></option>';
		foreach( $array as $key => $value ) {	
			$str .= '<option value="'.$key.'" '.($selected==$key?'selected="selected"':'').' >'.$value.'</option>';
		}
		return $str;
	}
	
	#Querystring functions
		static public function setInQuerystring($querystring, $key, $value){
			parse_str($querystring, $array);
			$array[$key] = $value;
			
			$querystring = array();
			foreach ( $array as $k => $v ) $querystring[] = $k ."=". $v;
			
			$querystring = implode("&", $querystring);
			return $querystring;
		}
			
		static public function unsetInQuerystring($querystring, $keys) {
			parse_str($querystring, $array);
			$keys = explode(',', $keys);
			foreach($keys as $key)	unset($array[trim($key)]);
	
			$querystring = array();
			foreach ( $array as $k => $v ) $querystring[] = $k ."=". $v;
			
			$querystring = implode("&", $querystring);
			return $querystring;
		}	
		
		static public function getFromQuerystring($querystring, $key) {
			parse_str($querystring, $array);
			return $array[$key];
		}
		
		#Unisce la seconda querystring alla prima sovrascrivendo eventuali intersezioni
		static public function addToQuerystring($querystring, $str) {
			parse_str($str, $array);
			foreach( $array as $k => $v ) {
				if ($v) $querystring = setInQuerystring($querystring, $k, $v);
				else 	$querystring = unsetInQuerystring($querystring, $k, $v);
			}
			return $querystring;
		}
	
	static public function full_rmdir( $dir ) {
		if ( !is_writable( $dir ) ) {
			if ( !@chmod( $dir, 0777 ) ) {
				return FALSE;
			}
		}		
		$d = dir( $dir );
		while ( FALSE !== ( $entry = $d->read() ) ){
			if ( $entry == '.' || $entry == '..' ){
				continue;
			}
			$entry = $dir . '/' . $entry;
			if ( is_dir( $entry ) ) {
				if ( !full_rmdir( $entry ) ) {
					return FALSE;
				}
				continue;
			}
            if ( !unlink( $entry ) ){
            	$d->close();
            	return FALSE;
            }
		}
		$d->close();
		rmdir( $dir );
		return TRUE;
	}
	
	static public function full_copy($src,$dst) {
	    $dir = opendir($src);
	    full_mkdir($dst);
	    while(false !== ( $file = readdir($dir)) ) {
	        if (( $file != '.' ) && ( $file != '..' ) && ( $file != '_svn' ) && ( $file != '.svn' )) {
	            if ( is_dir($src . '/' . $file) ) {
	                full_copy($src . '/' . $file,$dst . '/' . $file);
	            }
	            else {
	                copy($src . '/' . $file,$dst . '/' . $file);
	            }
	        }
	    }
	    closedir($dir);
	} 
	
	static public function full_mkdir($path, $mode = 0777) {
		$dirs = explode('/' , $path);		
		$count = count($dirs);
		$path = '.';
		for ($i = 0; $i < $count; $i++) {			
			$path .= '/' . $dirs[$i];			
			if (!is_dir($path) && !mkdir($path, $mode)) return false;
		}
		return true;
	}
	
	static public function php2js($str) { return str_replace("'","\\'",$str); }
	
	static public function createDatabase($server, $username, $password, $database) {
		$conn = mysql_connect($server,$username,$password);
		mysql_select_db($database);
		$sql = "CREATE DATABASE IF NOT EXISTS ".$database;
			if (!mysql_query($sql)) throw new Exception('Creating database('.$database.') error: '.mysql_error());
		mysql_close($conn);
	}
}