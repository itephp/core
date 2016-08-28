<?php


spl_autoload_register(function ($className) {
    $path=realpath(__DIR__.'/..');
	$classPath=str_replace('\\', '/', $className);
	if(strpos($classPath, 'ItePHP')===0){
		$classPath='src/'.substr($classPath, 7);
	}
	else{
		$classPath='test/'.$classPath;
	}
	$classPath=$path.'/'.$classPath.'.php';
        /** @noinspection PhpIncludeInspection */
        require_once $classPath;
});