<?php

define('ROOT', realpath(__DIR__.'/..'));

spl_autoload_register(function ($className) {
	$classPath=str_replace('\\', '/', $className);
	if(strpos($classPath, 'ItePHP')===0){
		$classPath='src/'.substr($classPath, 6);
	}
	else{
		$classPath='test/'.$classPath;		
	}
    require_once ROOT.'/'.$classPath.'.php';
});