<?php

/**
 * ItePHP: Freamwork PHP (http://php.iteracja.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://php.iteracja.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Core\Component\Form;

use ItePHP\Core\Component\Form\FormFormatter;
use ItePHP\Core\Component\Form\BasicFormFormatter;
use ItePHP\Core\Provider\Request;
use ItePHP\Core\Core\ValidatorService;
use ItePHP\Core\Validator\TextValidator;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.15.0
 */
class TextareaField extends FormField{

	private $data='';

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		if(!isset($options['validator'])){
			$this->setValidator(new TextValidator());
		}

		if(isset($options['value'])){
			$this->data=$options['value'];
			unset($options['value']);
		}

		parent::__construct($options);
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
	public function setData($value){
		$this->data=$value;
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		return $this->data;
	}

    /**
     * {@inheritdoc}
     */
	public function clearData(){
		return $this->data=null;
	}

    /**
     * {@inheritdoc}
     */
	public function componentRender(){
		$template='<textarea ';
		foreach($this->getTags() as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.htmlspecialchars($tag).'" ';
		}

		$template.='>'.htmlspecialchars($this->getData()).'</textarea>';

		return $template;
	}

}