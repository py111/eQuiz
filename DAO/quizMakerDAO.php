<?php
require_once("Connection.php");
//require_once("../Model/quiz_maker.php");
require_once(dirname(__FILE__)."\\..\\Model\\quiz_maker.php");


class QuizMakerMysqlDAO{

	
	public function write(Quiz_Maker $quiz_maker){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO quiz_maker ($username, $password, $email, $first_name, $last_name, $status) VALUES ( '%s,%s,%s,%s,%s,%s');", 
				mysql_real_escape_string($quiz_maker->getUserName()),
                                mysql_real_escape_string($quiz_maker->getPassword()),
                                mysql_real_escape_string($quiz_maker->getEmail()),
                                mysql_real_escape_string($quiz_maker->getFirstName()),
                                mysql_real_escape_string($quiz_maker->getLastName()),
                                mysql_real_escape_string($quiz_maker->getStatus()));
		$db->query($sql);
		$quiz_makerID = $db->getLastId();
 		mysql_close();
		
		if($quiz_makerId <= 0)
			throw new Exception("There was a problem with the database when inserting a new Quiz Maker");
		
		return $quiz_makerID;
		
	}
	
	public function update(Quiz_Maker $quiz_maker){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE quiz_maker SET username = '%s', password = '%s', email = '%s', first_name = '%s', last_name = '%s', status = '%s' WHERE id = %d",
				mysql_real_escape_string($quiz_maker->getUserName()),
                                mysql_real_escape_string($quiz_maker->getPassword()),
                                mysql_real_escape_string($quiz_maker->getEmail()),
                                mysql_real_escape_string($quiz_maker->getFirstName()),
                                mysql_real_escape_string($quiz_maker->getLastName()),
                                mysql_real_escape_string($quiz_maker->getStatus()),
				$quiz_maker->getID());
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not succesful DB problem");
		
			
	}
	
	
	public function readAll(){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, username, password, email, first_name, last_name, status FROM quiz_maker;");
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$quiz_makerTemp = new Quiz_Maker($newArray[0],$newArray[1],$newArray[2],$newArray[3],$newArray[4],$newArray[5],$newArray[6]);
				array_push($arr, $quiz_makerTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($quiz_makerID){
		$db = new myConnection();
                $sql = sprintf("SELECT id, username, password, email, first_name, last_name, status FROM quiz_maker WHERE id= %d", $quiz_makerID);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $quiz_maker = new Quiz_Maker($db->getField("id"),$db->getField("username"),$db->getField("password"),$db->getField("email"),$db->getField("first_name"),$db->getField("last_name"),$db->getField("status"));
                mysql_close();
                return $quiz_maker;         
        }
	
	public function authenticate($quizMakerName, $password){
		$db = new myConnection();
                $sql = sprintf("SELECT id FROM quiz_maker WHERE username = '%s' AND password = '%s'", 
									mysql_real_escape_string($quizMakerName),
									mysql_real_escape_string($password));
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return 0;
                $db->nextArray();
				$id = $db->getField("id");
                mysql_close();
                return $id;         
        }
}