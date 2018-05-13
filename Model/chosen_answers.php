<?php

class ChosenAnswer{
	
	private $id;
	private $member_takes_test_id;
	private $option_id;
	
	public function __construct($id = "",$member_takes_test_id ="",$option_id=""){
		
		$this->id = $id;
		$this->member_takes_test_id = $member_takes_test_id;
		$this->option_id = $option_id;
	}

	public function setOptionId($option_id){
		$this->option_id = $option_id;	
	}
	
	public function getOptionId(){
		return $this->option_id;	
	}
	
	public function getMemberTakesId(){
		return $this->member_takes_test_id;	
	}
	
	public function setMemberTakesTestId($member_takes_test_id){
		$this->member_takes_test_id = $member_takes_test_id;	
	}
	
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;	
	}
	
}