<?php

class Test{
	
	private $id;
	private $create_time;
	private $name;
	private $status;
	private $myHash;
	private $starting_time;
	private $ending_time;
	private $level_id;
	private $quiz_maker_id;
	
	const TEST_ACTIVE = 1;
	const TEST_NOT_ACTIVE = 0;
	
	const MAX_DATE = "01/01/2030";
	const MIN_DATE = "01/01/1970";
	
	public function __construct($id = "",$create_time = "", $name="", $status = "", 
							$myHash="", $starting_time="", $ending_time="", 
							$level_id="",$quiz_maker_id=""){
		
		$this->id = $id;
		$this->create_time = $create_time;
		$this->name = $name;
		$this->status = $status;
		$this->myHash = $myHash   ;
		$this->starting_time = $starting_time;
		$this->ending_time = $ending_time;
		$this->level_id = $level_id;
		$this->quiz_maker_id = $quiz_maker_id;
		
	}
	
	public function getQuizMakerId(){
		return $this->quiz_maker_id;	
	}
	
	public function setQuizMakerId($quiz_maker_id){
		$this->quiz_maker_id = $quiz_maker_id;	
	}
	
	public function getLevelId(){
		return $this->level_id;
	}	
	
	public function setLevelId($level_id){
		$this->level_id = $level_id;	
	}
	
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;	
	}
	
	public function getCreateTime(){
		return $this->create_time;	
	}
	public function createTest(){
		$this->create_time = date("Y-m-d H:i:s");
	}
	
	public function getName(){
		return $this->name;	
	}
	public function setName($name){
		if(!empty($name)){	$this->name = $name;
                }else{ throw new Exception("Test Name cannot be empty");
                }
                }
	
	public function getStatus(){
		return $this->status;	
	}
	public function setStatus($status){
		if($status == self::TEST_ACTIVE || $status == self::TEST_NOT_ACTIVE){
		$this->status = $status;
                }else{ throw new Exception("Invalid Status for test");
	}
        }

	public function getHash(){
		return $this->myHash;	
	}
	public function setHash($myHash){
		$this->myHash = $myHash;
	}	
	
	public function getStartingTime(){
		return $this->starting_time;	
	}
	public function setStartingTime($starting_time){
		$this->starting_time = $starting_time;
        }
	public function getEndingTime(){
		return $this->ending_time;	
	}
	public function setEndingTime($ending_time){
		$this->ending_time = $ending_time;
              
	
	 }


}