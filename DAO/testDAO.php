<?php
require_once("Connection.php");
//require_once("../Model/test.php");
require_once(dirname(__FILE__)."\\..\\Model\\test.php");


class TestMysqlDAO{
    public function write(Test $test){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO test (create_time,name,status, hash, starting_time, ending_time,level_id,quiz_maker_id) VALUES ( NOW(),'%s',%d,'%s','%s','%s',%d,%d);", 
				
                                mysql_real_escape_string($test->getName()),
                                $test->getStatus(),
                                $test->getHash(),
                                mysql_real_escape_string($test->getStartingTime()),
                                mysql_real_escape_string($test->getEndingTime()),
								$test->getLevelId(),
								$test->getQuizMakerId());
		$db->query($sql);
		$testID = $db->getLastId();
 		mysql_close();
		
		if($testID <= 0)
			throw new Exception("There was a problem with the database when inserting a new Test");
		
		return $testID;
		
	}
	
	public function update(Test $test){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE test SET create_time = '%s', name = '%s', status = %d, hash = '%d', starting_time = '%s', ending_time = '%s', level_id=%d, quiz_maker_id=%d WHERE id = %d",
				mysql_real_escape_string($test->getCreateTime()),
                                mysql_real_escape_string($test->getName()),
                                $test->getStatus(),
                                $test->getHash(),
                                mysql_real_escape_string($test->getStartingTime()),
                                mysql_real_escape_string($test->getEndingTime()),
								$test->getLevelId(),
								$test->getQuizMakerId(),
					$test->getID());
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not succesful DB problem");
		
			
	}
	
	
	public function readAll(){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, create_time, name, status, hash, starting_time, ending_time,level_id,quiz_maker_id  FROM test;");
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$testTemp = new Test($newArray[0],$newArray[1],$newArray[2],$newArray[3],$newArray[4],$newArray[5],$newArray[6],$newArray[7],$newArray[8]);
				array_push($arr, $testTemp);	
			}
			mysql_close();
			return $arr;		
	}
	public function readAllFrom($quizMakerID){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, create_time, name, status, hash, starting_time, ending_time,level_id,quiz_maker_id  FROM test WHERE quiz_maker_id=%d;",$quizMakerID);
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$testTemp = new Test($newArray[0],$newArray[1],$newArray[2],$newArray[3],$newArray[4],$newArray[5],$newArray[6],$newArray[7],$newArray[8]);
				array_push($arr, $testTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($testID){
		$db = new myConnection();
                $sql = sprintf("SELECT id, create_time, name, status, hash, starting_time, ending_time,level_id,quiz_maker_id  FROM test WHERE id= %d", $testID);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $test = new Test($db->getField("id"),
										$db->getField("create_time"),$db->getField("name"),
										$db->getField("status"),$db->getField("hash"),
										$db->getField("starting_time"),$db->getField("ending_time"),
										$db->getField("level_id"),$db->getField("quiz_maker_id")
								 );
                mysql_close();
                return $test;         
        }
}

