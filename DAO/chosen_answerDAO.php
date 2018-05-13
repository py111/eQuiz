<?php
//require_once("Connection.php");
//require_once("../Model/chosen_answers.php");
require_once(dirname(__FILE__)."\\..\\Model\\chosen_answers.php");
require_once(dirname(__FILE__)."\\Connection.php");


class ChosenAnswerMysqlDAO{

	
	public function write(ChosenAnswer $chosen_answer){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO chosen_answer (member_takes_test_id,options_id) VALUES ( %d,%d);", 
				$chosen_answer->getMemberTakesId(),
				$chosen_answer->getOptionId());
		$db->query($sql);
		$chosen_answerID = $db->getLastId();
 		mysql_close();
		
		if($chosen_answerID <= 0)
			throw new Exception("There was a problem with the database when inserting a new Chosen Answer");
		
		return $chosen_answerID;
		
	}
	
	public function update(ChosenAnswer $chosen_answer){
	
		$db = new myConnection();
		
		$sql = sprintf("UPDATE chosen_answer SET member_takes_test_id = %d, options_id = %d WHERE id = %d",
					$chosen_answer->getMemberTakesId(),
					$chosen_answer->getOptionId(),
					$chosen_answer->getID());
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not successful DB problem");
		
			
	}
	
	
	public function readAll(){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id,member_takes_test_id, options_id FROM chosen_answer;");
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$choosen_answerTemp = new ChosenAnswer($newArray[0],$newArray[1],$newArray[2]);;
				array_push($arr, $choosen_answerTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function getChosenAnswerFromMemberTakesTest($member_takes_id){
		$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id,member_takes_test_id, options_id FROM chosen_answer WHERE member_takes_test_id = %d;",$member_takes_id);
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$choosen_answerTemp = new ChosenAnswer($newArray[0],$newArray[1],$newArray[2]);;
				array_push($arr, $choosen_answerTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($chosen_answerID){
		$db = new myConnection();
                $sql = sprintf("SELECT id,member_takes_test_id,options_id  FROM chosen_answer WHERE id= %d", $chosen_answerID);
				$db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $choosen_answer = new ChosenAnswer($db->getField("id"),
												$db->getField("member_takes_test_id"),
												$db->getField("options_id"));
                mysql_close();
                return $choosen_answer;         
      }
	  
	  
	  public function questionAlreadyAnswered($question_id, $memberTakes){
		  
		  $db = new myConnection();
		  $sql = sprintf("SELECT chosen_answer.id AS id,chosen_answer.member_takes_test_id AS member_takes_test,chosen_answer.options_id AS options_id FROM chosen_answer,options WHERE options.questions_id = %d AND chosen_answer.options_id = options.id AND chosen_answer.member_takes_test_id = %d ",$question_id, $memberTakes);
		  $db->query($sql);
                if($db->getNumResults()<=0){
					return 0;
				}
                $db->nextArray();
				
				$myChosenAnswer = new ChosenAnswer($db->getField("id"),
												$db->getField("member_takes_test"),
												$db->getField("options_id")); 
                mysql_close();
                return  $myChosenAnswer;
	  }
	  
	  
	  
}