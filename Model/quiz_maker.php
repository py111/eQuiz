<?php

class Quiz_Maker{
	
	private $id;
	private $username;
	private $password;
	private $email;
	private $first_name;
	private $last_name;
	private $status;
	
	const QUIZ_MAKER_ACTIVE = 1;
	const QUIZ_MAKER_NOT_ACTIVE = 0;
	
	public function __construct($id = "",$username = "", $password="", $email = "", $first_name="", $last_name="", $status=""){
		
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->status = $status;
		
	}
	
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;	
	}
	
	public function getUserName(){
		return $this->username;	
	}
	public function setUserName($userName){
		if(!empty($userName))	$this->username = strtolower($userName);
		else throw new Exception("The username field cannot be empty");
	}
	
	public function getPassword(){
		return $this->password;	
	}
	public function setPassword($password){
		if(!empty($password))			$this->password = $password;
		else throw new Exception("Password field cannot be empty");
	}
	
	public function getEmail(){
		return $this->email;	
	}
	public function setEmail($email){
		if(!empty($email))		$this->username = strtolower($email);
		else throw new Exception("Email field cannot be empty");
	}

	public function getFirstName(){
		return $this->first_name;	
	}
	public function setFirstName($first_name){
		if(!empty($first_name))	$this->first_name = $first_name;
		else throw new Exception("First Name field cannot be empty");
	}
	
	public function getLastName(){
		return $this->last_name;	
	}
	public function setLastName($last_name){
		if(!empty($last_name))	$this->last_name = $last_name;
		else throw new Exception("Last name field cannot be empty");
	}
	
	public function getStatus(){
		return $this->status;	
	}
	public function setStatus($status){
		if($status == self::QUIZ_MAKER_ACTIVE || $status == self::QUIZ_MAKER_NOT_ACTIVE)
			$this->status = $status;
		else
			throw new Exception("Quiz maker status not valid");
	}
}