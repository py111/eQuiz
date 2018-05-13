<?php
require_once("Connection.php");
//require_once("../Model/questions.php");
require_once(dirname(__FILE__)."\\..\\Model\\questions.php");


class QuestionsMysqlDAO{

	
	public function write(Question $questions){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO questions (text,status,test_id) VALUES ( '%s',%d,%d);", 
								mysql_real_escape_string($questions->getText()),
                                $questions->getStatus(),
								$questions->getTestId());
		$db->query($sql);
		$questionsID = $db->getLastId();
 		mysql_close();
		
		if($questionsID <= 0)
			throw new Exception("There was a problem with the database when inserting a new Questions");
		
		return $questionsID;
		
	}
	
	public function update(Question $questions){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE  questions SET text = '%s', status = '%s', test_id = %d WHERE id = %d",
										mysql_real_escape_string($questions->getText()),
                                       $questions->getStatus(),
									   $questions->getTestId(),
									   $questions->getID());
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not succesful DB problem");
		
			
	}
	
	
	public function readAllFrom($test_id){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, text, status, test_id FROM questions WHERE test_id = %d",$test_id);
			
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$questionsTemp = new Question($newArray[0],$newArray[1],$newArray[2],$newArray[3]);
				array_push($arr, $questionsTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
		public function readAllFromTest($test_id){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, text, status, test_id FROM questions WHERE test_id = %d;",$test_id);
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$questionsTemp = new Question($newArray[0],$newArray[1],$newArray[2],$newArray[3]);
				array_push($arr, $questionsTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function getNumberQuestionsFromTest($test_id){
		$db = new myConnection();
                $sql = sprintf("SELECT COUNT(id) as counter FROM questions WHERE test_id= %d", $test_id);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $counter = $db->getField("counter");
                mysql_close();
                return $counter;    
	}
	
	public function search($questionsID){
		$db = new myConnection();
                $sql = sprintf("SELECT id, text, status, test_id FROM questions WHERE id= %d", $questionsID);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $questions = new Question($db->getField("id"),$db->getField("text"),$db->getField("status"),$db->getField("test_id"));
                mysql_close();
                return $questions;         
        }
	
}