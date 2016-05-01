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

use ItePHP\Component\Form\FormFormatter;
use ItePHP\Component\Form\BasicFormFormatter;
use ItePHP\Component\Form\BasicFormTransformer;
use ItePHP\Component\Form\Designer;
use ItePHP\Component\Form\Transformer;
use ItePHP\Core\RequestProvider;
use ItePHP\Core\ValidatorService;
use ItePHP\Exception\FieldNotFoundException;
use ItePHP\Exception\FileNotUploadedException;
use ItePHP\Core\FileUploaded;
use ItePHP\Component\Form\FormField;

/**
 * Generator form. Support for mapping data, validation and generate html code.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.13.0
 */
class FormBuilder{
	private $formatter;
	private $fields=array();
	private $formTags=array();
	private $submitTags=array();
	private $validatorService;
	private $isConfirmed=false;
	private $designer;
	private $transformer;

	public function __construct(){
		$this->formatter=new BasicFormFormatter();
		$this->transformer=new BasicFormTransformer();
		$this->formTags=array(
			'method'=>'post'
			,'id'=>null
			,'class'=>null
			,'enctype'=>null
			);

		$this->submitTags=array(
			'value'=>'Apply'
			,'id'=>null
			,'class'=>null);
	}

	/**
	 * Set service to validate field data
	 *
	 * @param \ItePHP\Core\ValidatorService $validatorService
	 * @since 0.13.0
	 */
	public function setValidatorService(ValidatorService $validatorService){
		$this->validatorService=$validatorService;
	}

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param \ItePHP\Component\Form\FormFormatter $formatter
	 * @since 0.13.0
	 */
	public function setFormatter(FormFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set designer with rule to generate fields
	 *
	 * @param \ItePHP\Component\Form\Designer $designer
	 * @since 0.18.0
	 */
	public function setDesigner(Designer $designer){
		$this->designer=$designer;
		$this->designer->build($this);
	}

	/**
	 * Set transformer with rule to encode/decode data
	 *
	 * @param \ItePHP\Component\Form\Transformer $transformer
	 * @since 0.22.0
	 */
	public function setTransformer(Transformer $transformer){
		$this->transformer=$transformer;
	}

	/**
	 * Set addon form tags
	 *
	 * @param array $tags - array widh data (all field is optional):
	 * array(
	 *	'method'=>'post' //"post" or "get"
	 *	,'id'=>'id1' //html tag id
	 *	,'class'=>'class1' //html tag class
	 *	,'enctype'=>'multipart/form-data' //html tag enctype eg. "text/plain", "multipart/form-data" or "application/x-www-form-urlencoded" 
	 *	)
	 * @since 0.13.0
	 */
	public function setFormTags($tags){
		$this->formTags=array_merge($this->formTags,$tags);
	}

	/**
	 * Set submit button tags
	 *
	 * @param array $tags - arrawy with data:
	 * array(
	 * 	'value'=> 'Apply' //label button, default: Apply
	 * 	,'id'=>'id1' //html tag id
	 * 	,'class'=>'class1' //html tag class
	 * )
	 * @since 0.13.0
	 */
	public function setSubmitTags($tags){
		$this->submitTags=array_merge($this->submitTags,$tags);
	}

	/**
	 * Add form field
	 *
	 * @param \ItePHP\Component\Form\FormField $field
	 * @since 0.15.0
	 */
	public function addField(FormField $field){

		$this->fields[]=$field;

		if($field->getName()==null){
			$field->setName('name_'.count($this->fields));
		}

		if($field->getId()==null){
			$field->setId('id_'.count($this->fields));
		}

		if($field instanceof FileField){
			$this->formTags['enctype']='multipart/form-data';
		}

	}

	/**
	 * Remove field from generator
	 *
	 * @param string $name - field name
	 * @since 0.18.0
	 */
	public function removeField($name){
		for($i=0; $i<count($this->fields); $i++){
			if($this->fields[$i]->getName()==$name){
				array_splice($this->fields,$i,1);
				break;
			}
		}
	}

	/**
	 * Generate html form string
	 *
	 * @return string - with html form
	 * @since 0.13.0
	 */
	public function render(){
		$html=$this->formatter->renderFormBegin($this->formTags);
		foreach($this->fields as $field){
			$html.=$this->formatter->renderField($field);
		}

		$html.=$this->renderSubmit();
		$html.=$this->renderEnd();
		return $html;
	}

	/**
	 * Generate html string for fields
	 *
	 * @return string with html fields
	 * @since 0.22.0
	 */
	public function renderFields(){
		$html='';

		foreach($this->fields as $field){
			$html.=$this->formatter->renderField($field);
		}

		return $html;
	}

	/**
	 * Generate html string for selected field
	 *
	 * @param string $name - field name
	 * @return string with html field
	 * @since 0.16.0
	 */
	public function renderField($name){
		$html='';

		$field=$this->getField($name);
		$html.=$this->formatter->renderField($field);

		return $html;
	}

	/**
	 * Generate html string for open form tag
	 *
	 * @return string - with html open form tag
	 * @since 0.16.0
	 */
	public function renderBegin(){
		return $this->formatter->renderFormBegin($this->formTags);
	}

	/**
	 * Generate html string for close form tag
	 *
	 * @return string - with html close form tag
	 * @since 0.16.0
	 */
	public function renderEnd(){
		return $this->formatter->renderFormEnd();
	}

	/**
	 * Generate html string for open form submit
	 *
	 * @return string - with html open form submit
	 * @since 0.16.0
	 */
	public function renderSubmit(){
		return $this->formatter->renderSubmit($this->submitTags);
	}

	/**
	 * @return string - with html form
	 * @since 0.16.0
	 */
	public function __toString(){
		return $this->render();
	}

	/**
	 * Get field object
	 *
	 * @param string $name - field name (html name tag)
	 * @return \ItePHP\Component\Form\FormField
	 * @throws \ItePHP\Exception\FieldNotFoundException - invalid param name
	 * @since 0.13.0
	 */
	public function getField($name){
		foreach($this->fields as $field){
			if($field->getName()==$name)
				return $field;
		}

		throw new FieldNotFoundException($name);
	}

	/**
	 * Check confirmed form (clicked submit button in frontend/sended fields value)
	 *
	 * @return boolean - if success then true else false
	 * @since 0.13.0
	 */
	public function isConfirmed(){
		return $this->isConfirmed;
	}

	/**
	 * Check valid form
	 *
	 * @return boolean - if success then true else false
	 * @since 0.13.0
	 */
	public function isValid(){
		if(!$this->isConfirmed())
			return false;

		$errors=$this->getErrors();
		
		return count($errors)==0;

	}

	/**
	 * Set default values for fiels
	 *
	 * @param array $data eg:
	 * array(
	 * '{text field name 1}'=>'{text value name 1}'
	 * ,'{text field name 2}'=>'{text value name 2}'
	 * )
	 * @since 0.13.0
	 */
	public function setData($data){//FIXME aktualnie muszą być dodane pola, aby ustawił wartości. Trzeba by zmienić by zachowywał dane, a potem je ustawiał podczas renderowania lub walidowania
		$data=$this->transformer->encode($data);
		foreach($this->fields as $field){
			if(isset($data[$field->getName()])){
				$field->setData($data[$field->getName()]);
			}
		}
	}

	/**
	 * Get data from fields
	 *
	 * @return array
	 * @since 0.17.0
	 */
	public function getData(){
		$data=array();
		foreach($this->fields as $field){
			if(preg_match('/^(.*?)(\[.*\])$/',$field->getName(),$result)){
				if($result[2]==''){
					//FIXME autoincrement field
				}
				else{

					if(!preg_match_all("/\[(.*?)\]/", $result[2], $resultDeep)){
						throw new \Exception('Invalid field name.');//FIXME dedicate exception
					}
					$storage=&$data[$result[1]];
					foreach($resultDeep[1] as $deep){
						if(!isset($storage[$deep])){
							$storage[$deep]=array();
						}
						$storage=&$storage[$deep];
					}
					$storage=$field->getData();
				}
			}
			else{
				$data[$field->getName()]=$field->getData();
			}
		}

		return $this->transformer->decode($data);
	}

	/**
	 * Remove all field data
	 *
	 * @since 0.17.0
	 */
	public function clearData(){
		$data=array();
		foreach($this->fields as $field){
			$field->clearData();
		}
	}

	/**
	 * Submit form. Check http confirm and validate fields
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @since 0.17.0
	 */
	public function submit(RequestProvider $request){
		$this->isConfirmed=false;

		if($this->formTags['method']=='post' && $request->getType()=='POST'){
			$this->isConfirmed=true;
		}

		$query=$request->getQuery();
		if(count($this->fields)>0 && $this->formTags['method']=='get' && isset($query[$this->fields[0]->getName()])){
			$this->isConfirmed=true;
		}

		if(!$this->isConfirmed)
			return;

		$storage=array();
		if($this->formTags['method']=='post'){
			$storage=$request->getData();
		}
		else{
			$storage=$request->getQuery();			
		}

		//set field data

		$result=array();
		foreach($this->fields as $field){

			if(isset($storage[$field->getName()])){
				$field->setData($storage[$field->getName()]);
			}
			else if($field instanceof FileField){
				try{
					$field->setData($request->getFile($field->getName()));
				}
				catch(FileNotUploadedException $e){
					$field->setData('');
				}
			}
			else if(preg_match('/^(.*?)(\[.*\])$/',$field->getName(),$result)){//array
				if(!preg_match_all("/\[(.*?)\]/", $result[2], $resultDeep)){
					throw new \Exception('Invalid field name.');//FIXME dedicate exception
				}

				$value=$storage[$result[1]];
				foreach($resultDeep[1] as $deep){
					if(!isset($value[$deep])){
						$value=null;
						break;
					}
					$value=$value[$deep];
				}

				if($result[2]==''){
					//FIXME autoincrement field
				}
				else{
					$field->setData($value);
				}
			}
			else{//for checkbox
				$field->setData(null);
			}
		}

		//validate
		if($request->isFullUploadedData()){
			foreach($this->fields as $field){
				if($field->getValidator()){
					if($error=$this->validatorService->validate($field->getValidator(),$field->getData())){
						$field->setError($error);
					}

				}
			}
		}
		else{
			foreach($this->fields as $field){
				$field->setError('Request data is too large.');
			}
		}
	}

	/**
	 * Validate fields and get errors
	 *
	 * @return array - with errors if success then empty array
	 * @since 0.13.0
	 */
	public function getErrors(){
		$errors=array();
		foreach($this->fields as $field){
			if(!$field->isValid()){				
				$errors[]=array('field'=>$field->getLabel(),'message'=>$field->getError());
			}
		}

		return $errors;
	}
}
