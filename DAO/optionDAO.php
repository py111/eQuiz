<?php
require_once("Connection.php");
//require_once("../Model/options.php");
require_once(dirname(__FILE__)."\\..\\Model\\options.php");


class OptionsMysqlDAO{

	
	public function write(Options $options){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO options (text, correct_flag, questions_id) VALUES ( '%s',%d,%d);", 
								mysql_real_escape_string($options->getText()),
                                $options->getCorrectFlag(),
								$options->getQuestionsID());
		$db->query($sql);
		$optionsID = $db->getLastId();
 		mysql_close();
		
		if($optionsID <= 0)
			throw new Exception("There was a problem with the database when inserting a new Option");
		
		return $optionsID;
		
	}
	
	public function update(Options $options){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE  options SET text = '%s', correct_flag = %d, questions_id = %d WHERE id = %d",
					mysql_real_escape_string($options->getText()),
                                        $options->getCorrectFlag(),
										$options->getQuestionsID(),
										$options->getID());
					
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not succesful DB problem");
		
		
	}
	
	
	public function readAllFromQuestion($question_id){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, text, correct_flag, questions_id FROM options WHERE questions_id = %d",$question_id);
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$optionsTemp = new Options($newArray[0],$newArray[1],$newArray[2],$newArray[3]);
				array_push($arr, $optionsTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($optionsID){
		$db = new myConnection();
                $sql = sprintf("SELECT id, text, correct_flag, questions_id FROM options WHERE id= %d", $optionsID);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $options = new Options($db->getField("id"),$db->getField("text"),$db->getField("correct_flag"),$db->getField("questions_id"));
                mysql_close();
                return $options;         
        }
		
		public function getCorrectOptionFrom($question_id){
			
				$db = new myConnection();
                $sql = sprintf("SELECT id, text, correct_flag, questions_id FROM options WHERE correct_flag=1 AND questions_id = %d", $question_id);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $options = new Options($db->getField("id"),$db->getField("text"),$db->getField("correct_flag"),$db->getField("questions_id"));
                mysql_close();
                return $options;         
        }
	
}