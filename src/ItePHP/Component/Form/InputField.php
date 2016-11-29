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

namespace ItePHP\Component\Form;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
abstract class InputField extends FormField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		parent::__construct($options);
	}

	/**
	 * Set html tag pattern
	 *
	 * @param string $pattern - value of tag pattern (regular expression)
	 */
	public function setPattern($pattern){
		$this->setTag('pattern',$pattern);
		if($this->getValidator()){
			$this->getValidator()->setOption('pattern',$pattern);
		}
	}

	/**
	 * Get value of html tag pattern
	 *
	 * @return string
	 */
	public function getPattern(){
		return $this->getTag('pattern');		
	}

	/**
	 * Set value field
	 *
	 * @param mixed $value - value field
	 */
	public function setValue($value){
		$this->setTag('value',$value);
	}

	/**
	 * Get value field
	 *
	 * @return mixed
	 */
	public function getValue(){
		return $this->getTag('value');		
	}

    /**
     * {@inheritdoc}
     */
	public function render(){
		$template=$this->labelRender();
		$template.=$this->componentRender();
		return $template;
	}

    /**
     * {@inheritdoc}
     */
	public function labelRender(){
		return '<label for="'.$this->getId().'">'.htmlspecialchars($this->getLabel()).'</label>';
	}

    /**
     * {@inheritdoc}
     */
	public function componentRender(){
		$template='<input ';
		foreach($this->getTags() as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.htmlspecialchars($tag).'" ';
		}
		$template.=' />';
		return $template;

	}
	
    /**
     * {@inheritdoc}
     */
	public function setData($value){
		$this->setValue($value);
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		return $this->getValue();
	}

    /**
     * {@inheritdoc}
     */
	public function clearData(){
		return $this->setValue(null);
	}
}