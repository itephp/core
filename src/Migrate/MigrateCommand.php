<?php

/**
 * ItePHP: Framework PHP (http://itephp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://itephp.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Migrate;

use ItePHP\Command\CommandInterface;
use ItePHP\Command\CommandConfig;
use ItePHP\Command\InputStream;
use ItePHP\Command\OutputStream;

use ItePHP\Core\Container;
use ItePHP\Core\Enviorment;

class MigrateCommand implements CommandInterface{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var string
	 */
	private $patternPath;

	/**
	 * @var string
	 */
	private $savePath;

	/**
	 *
	 * @param Container $container
	 * @param Enviorment $enviorment
	 */
	public function __construct(Container $container,Enviorment $enviorment){
		$this->container=$container;
		$this->setPatternPath('/vendor/itephp/framework/pattern/migrate.txt');
		$this->setSavePath('/config/migrate.'.$enviorment->getName().'.txt');
		$this->setStagePath('/src/Migrate');
	}

	/**
	 *
	 * @param string $path
	 */
	public function setPatternPath($path){
		$this->patternPath=$path;
	}

	/**
	 *
	 * @param string $path
	 */
	public function setSavePath($path){
		$this->savePath=$path;
	}

	/**
	 *
	 * @param string $path
	 */
	public function setStagePath($path){
		$this->stagePath=$path;
	}

    /**
     * {@inheritdoc}
     */
	public function doConfig(CommandConfig $config){
		$config->addArgument('-o',1);
	}

    /**
     * {@inheritdoc}
     */
	public function execute(InputStream $in,OutputStream $out){
		$operation=$in->getArgument('-o');
		switch($in->getArgument('-o')){
			case 'create':
				$this->createOperation($in,$out);
			break;
			case 'upgrade':
				$this->upgradeOperation($in,$out);
			break;
			case 'downgrade':
				$this->downgradeOperation($in,$out);
			break;
			default:
				throw new OperationNotSupportedException($operation);
		}
	}

	/**
	 *
	 * @param InputStream $in
	 * @param OutputStream $out
	 */
	public function createOperation(InputStream $in,OutputStream $out){
		$now=new \DateTime();

		$template=file_get_contents(ITE_ROOT.$this->patternPath);
		$template=str_replace('${date}', $now->format('YmdHis'), $template);
		$path=ITE_ROOT.$this->stagePath.'/Version'.$now->format('YmdHis').'.php';
		file_put_contents($path, $template);
		$out->write('File created: '.$path);
		$out->flush();
	}

	/**
	 *
	 * @param InputStream $in
	 * @param OutputStream $out
	 */
	public function upgradeOperation(InputStream $in,OutputStream $out){
		$currentVersion=0;
		if(file_exists($this->getSavePath())){
			$currentVersion=file_get_contents($this->getSavePath());			
		}

		$migrateFiles=[];
		$handleDir=opendir(ITE_ROOT.$this->stagePath);
		while($file=readdir($handleDir)){
			if($file!="." && $file!=".." && preg_match('/^Version([0-9]+)\.php$/',$file,$match)){
				$migrateFiles[]=$match[1];
			}
		}

		$versionBefore=$currentVersion;
		sort($migrateFiles);
		foreach($migrateFiles as $migrateFile){
			try{
				if($migrateFile>$currentVersion){
					require_once(ITE_ROOT.$this->stagePath.'/Version'.$migrateFile.'.php');
					$versionClassName='Migrate\Version'.$migrateFile;
					$versionObject=new $versionClassName();

					$versionObject->up($this->container);
					$currentVersion=$migrateFile;
					file_put_contents($this->getSavePath(), $currentVersion);

				}

			}
			catch(\Exception $e){
				throw new \Exception("Error in version ".$migrateFile."(".$e->getLine().") [upgrade]: ".$e->getMessage());
			}
		}

		$out->write("Modified version ".$versionBefore." to ".$currentVersion);
	}

	/**
	 *
	 * @param InputStream $in
	 * @param OutputStream $out
	 */
	public function downgradeOperation(InputStream $in,OutputStream $out){
		$currentVersion=0;
		if(file_exists($this->getSavePath())){
			$currentVersion=file_get_contents($this->getSavePath());			
		}

		$migrateFiles=array();
		$handleDir=opendir(ITE_ROOT.$this->stagePath);
		while($file=readdir($handleDir)){
			if($file!="." && $file!=".." && preg_match('/^Version([0-9]+)\.php$/',$file,$match)){
				$migrateFiles[]=$match[1];
			}
		}
		$versionBefore=$currentVersion;
		rsort($migrateFiles);
		foreach($migrateFiles as $migrateFile){
			try{
				if($migrateFile<=$currentVersion){
					require_once(ITE_ROOT.$this->stagePath.'/Version'.$migrateFile.'.php');
					$versionClassName='Migrate\Version'.$migrateFile;
					$versionObject=new $versionClassName();

					$versionObject->down($this->container);
					$this->currentVersion=$migrateFile;
					file_put_contents($this->getSavePath(), $currentVersion);

				}

			}
			catch(\Exception $e){
				throw new \Exception("Error in version ".$migrateFile."(".$e->getLine().") [downgrade]: ".$e->getMessage());
			}
		}

		$currentVersion=0;
		file_put_contents($this->getSavePath(), $currentVersion);

		$out->write("Modified version ".$versionBefore." to ".$currentVersion);

	}



	/**
	 * Get url to file with saved current stage
	 *
	 * @return string
	 */
	private function getSavePath(){
		return ITE_ROOT.$this->savePath;
	}

}