<?php

class Member{
	
	private $id;
	private $username;
	private $password;
	private $email;
	private $first_name;
	private $last_name;
	private $status;
	
	const MEMBERACTIVE = 1;
	const MEMBERNOTACTIVE = 0;
	
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
		if(!empty($userName)){
			$this->username = strtolower($userName);
		}else throw new Exception("Not empty username allowed");
	}
	
	public function getPassword(){
		return $this->password;	
	}
	public function setPassword($password){
		if(!empty($password) && sizeof($password) >= 3){
			$this->password = md5($password);
		}
		else throw new Exception("Not empty password allowed/ Password must contain at least 3 characters");
	}
	
	public function getEmail(){
		return $this->email;	
	}
	public function setEmail($email){
		if(!empty($email)){
			$this->username = strtolower($email);
		}else{
			throw new Exception("Not empty emails allowed");	
		}
	}

	public function getFirstName(){
		return $this->first_name;	
	}
	public function setFirstName($first_name){
		if(!empty($first_name)){
			$this->first_name = $first_name;
		}else throw new Exception("Not empty first names allowed");
	}
	
	public function getLastName(){
		return $this->last_name;	
	}
	public function setLastName($last_name){
		if(!empty($last_name)){
			$this->last_name = $last_name;
		}else throw new Exception("Not empty last names allowed");
	}
	public function getStatus(){
		return $this->status;	
	}
	public function setStatus($status){
		if($status == self::MEMBERACTIVE || $status == self::MEMBERNOTACTIVE)
			$this->status = $status;
		else  throw new Exception("Invalid Member Status");
	}
}