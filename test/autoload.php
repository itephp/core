<?php

$vendors=[
    'Via'=>'vendor/iteracja/via/src',
    'ItePHP'=>'src',
];

spl_autoload_register(function ($className) use ($vendors){
    $path=realpath(__DIR__.'/..');
	$classPath=str_replace('\\', '/', $className);
	if(strpos($classPath, 'Test')===0){
        $classPath='test/'.substr($classPath, 5);
	}
	else{
	    foreach($vendors as $kVendor=>$vendor){
            if(strpos($classPath, $kVendor)===0){
                $classPath=$vendor.'/'.$classPath;
                break;
            }

        }
	}
	$classPath=$path.'/'.$classPath.'.php';
    if(!file_exists($classPath)){
//        echo $classPath; exit;
        return;
    }
    /** @noinspection PhpIncludeInspection */
    require_once $classPath;
});