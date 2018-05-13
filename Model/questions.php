<?php

class Question{
	
	private $id;
	private $text;
	private $status;
	private $test_id;
	
	const QUESTION_ACTIVE = 1;
	const QUESTION_NOT_ACTIVE = 0;
	
	public function __construct($id = "",$text = "", $status="", $test_id=""){
		
		$this->id = $id;
		$this->text = $text;
		$this->status = $status;
		$this->test_id = $test_id;
				
	}
	
	public function getTestId(){
		return $this->test_id;	
	}
	
	public function setTestId($test_id){
		$this->test_id = $test_id;	
	}
	
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;	
	}
	
	public function getText(){
		return $this->text;	
	}
	public function setText($text){
		if(!empty($text))
			$this->text = $text;
		else throw new Exception("No empty questions allowed");
	}
	
	public function getStatus(){
		return $this->status;	
	}
	public function setStatus($status){
		if($status == self::QUESTION_ACTIVE || $status == self::QUESTION_NOT_ACTIVE)
		$this->status = $status;
		else throw new Exception("Invalid Status for question");
	}

}