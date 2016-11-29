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
use ItePHP\Core\Environment;

/**
 * Migrate command.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class MigrateCommand implements CommandInterface{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var Environment
	 */
	private $environment;

	/**
	 * @var string
	 */
	private $patternPath;

	/**
	 * @var string
	 */
	private $savePath;

    /**
     * @var string
     */
    private $stagePath;

    /**
	 *
	 * @param Container $container
	 * @param Environment $environment
	 */
	public function __construct(Container $container, Environment $environment){
		$this->container=$container;
		$this->environment=$environment;
		$this->setPatternPath('/vendor/itephp/framework/pattern/migrate.txt');
		$this->setSavePath('/config/migrate.'.$this->environment->getName().'.txt');
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
				$this->createOperation($out);
			break;
			case 'upgrade':
				$this->upgradeOperation($out);
			break;
			case 'downgrade':
				$this->downgradeOperation($out);
			break;
			default:
				throw new OperationNotSupportedException($operation);
		}
	}

	/**
	 *
	 * @param OutputStream $out
	 */
	public function createOperation(OutputStream $out){
		$now=new \DateTime();

		$template=file_get_contents($this->getPatternPath());
		$template=str_replace('${date}', $now->format('YmdHis'), $template);
		$path=$this->getStagePath().'/Version'.$now->format('YmdHis').'.php';
		file_put_contents($path, $template);
		$out->write('File created: '.$path);
		$out->flush();
	}

	/**
	 *
	 * @param OutputStream $out
	 * @throws \Exception
	 */
	public function upgradeOperation(OutputStream $out){
		$currentVersion=0;
		if(file_exists($this->getSavePath())){
			$currentVersion=file_get_contents($this->getSavePath());			
		}

		$migrateFiles=[];
		$handleDir=opendir($this->getStagePath());
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
				    $versionFile=$this->getStagePath().'/Version'.$migrateFile.'.php';
                    /** @noinspection PhpIncludeInspection */
                    require_once($versionFile);
					$versionClassName='Migrate\Version'.$migrateFile;
                    /**
                     * @var MigrateStage $versionObject
                     */
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
		$out->flush();
	}

	/**
	 *
	 * @param OutputStream $out
	 * @throws \Exception
	 */
	public function downgradeOperation(OutputStream $out){
		$currentVersion=0;
		if(file_exists($this->getSavePath())){
			$currentVersion=file_get_contents($this->getSavePath());			
		}

		$migrateFiles=array();
		$handleDir=opendir($this->getStagePath());
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
				    $versionFile=$this->getStagePath().'/Version'.$migrateFile.'.php';
                    /** @noinspection PhpIncludeInspection */
                    require_once($versionFile);
					$versionClassName='Migrate\Version'.$migrateFile;
					$versionObject=new $versionClassName();

                    /**
                     * @var MigrateStage $versionObject
                     */
                    $versionObject->down($this->container);
					$currentVersion=$migrateFile;
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
		$out->flush();
	}



	/**
	 *
	 * @return string
	 */
	private function getSavePath(){
		return $this->environment->getRootPath().$this->savePath;
	}

	/**
	 *
	 * @return string
	 */
	private function getStagePath(){
		return $this->environment->getRootPath().$this->stagePath;
	}

	/**
	 *
	 * @return string
	 */
	private function getPatternPath(){
		return $this->environment->getRootPath().$this->patternPath;
	}

}