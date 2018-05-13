<?php

class Level{
	
	private $id;
	private $name;	
	
	public function __construct($id = "",$name = ""){
		
		$this->id = $id;
		$this->name = $name;				
	}
	
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;	
	}
	
	public function getName(){
		return $this->name;	
	}
	public function setName($name){
		
		$this->name = $name;
	}
}