<?php

class MemberTakesTest{
	
	private $id;
	private $starting_time;
	private $ending_time;
	private $complete_flag;
	private $grade;
	private $member_id;
	private $test_id;
	
	const MAXGRADE = 100;
	const MINGRADE = 0;
	const NOT_GRADED_YET = -1;
	
	public function __construct($id = "",$member_id="",$test_id="" ,$starting_time="", $ending_time="", $complete_flag=0, $grade=self::NOT_GRADED_YET){
		
		$this->id = $id;
		$this->member_id = $member_id;
		$this->test_id = $test_id;
		$this->starting_time = $starting_time;
		$this->ending_time = $ending_time;
		$this->complete_flag = $complete_flag;
		$this->grade = $grade;		
	}
	
	public function getTestId(){
		return $this->test_id;	
	}
	
	public function setTestId($test_id){
		$this->test_id = $test_id;
	}
	
	public function getMemberId(){
		return $this->member_id;	
	}
	
	public function setMemberId($member_id){
		$this->member_id = $member_id;	
	}
	
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;	
	}
	
	public function getStartingTime(){
		return $this->starting_time;	
	}
	public function startTest(){
		
		$this->starting_time = date("Y-m-d H:i:s");
	}	

	public function getEndingTime(){
		return $this->ending_time;	
	}
	public function endTest(){
		$this->ending_time = date("Y-m-d H:i:s");
		$this->setAsCompleted();
	}	

	public function getCompleteFlag(){
		return $this->complete_flag;
	}
	
	//Once is completed, you cannot go back
	private function setAsCompleted(){
		$this->complete_flag = true;
	}
	
	public function getGrade(){
		return $this->grade;
	}
	public function setGrade($grade){
		if( $grade<=self::MAXGRADE && $grade >= self::MINGRADE ){
			$this->grade = $grade;
		}else{
			throw new Exception("Grade out of bounds");	
		}
	}

}