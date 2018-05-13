<?php

class Options{
	
	private $id;
	private $text;
	private $correct_flag;
	private $questions_id;
	
	
	public function __construct($id = "",$text = "", $correct_flag="", $questions_id = ""){
		
		$this->id = $id;
		$this->text = $text;
		$this->correct_flag = $correct_flag;
		$this->questions_id = $questions_id;
				
	}
	
	public function setQuesitionsId($questions_id){
		$this->questions_id = $questions_id;	
	}
	
	public function getQuestionsID(){
		return $this->questions_id;	
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
	}
	
	public function getCorrectFlag(){
		return $this->correct_flag;	
	}
	public function setCorrectFlag($correct_flag){
		
		$this->correct_flag = $correct_flag;
	}

}