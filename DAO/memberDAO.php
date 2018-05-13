<?php
require_once("Connection.php");
//require_once("../Model/member.php");
require_once(dirname(__FILE__)."\\..\\Model\\member.php");

class MemberMysqlDAO{

	
	public function write(Member $member){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO member ($username, $password, $email, $first_name, $last_name, $status) VALUES ( '%s,%s,%s,%s,%s,%s');", 
				mysql_real_escape_string($member->getUserName()),
                                mysql_real_escape_string($member->getPassword()),
                                mysql_real_escape_string($member->getEmail()),
                                mysql_real_escape_string($member->getFirstName()),
                                mysql_real_escape_string($member->getLastName()),
                                mysql_real_escape_string($member->getStatus()));
		$db->query($sql);
		$memberID = $db->getLastId();
 		mysql_close();
		
		if($memberId <= 0)
			throw new Exception("There was a problem with the database when inserting a new Member");
		
		return $memberID;
		
	}
	
	public function update(Member $member){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE  member SET username = '%s', password = '%s', email = '%s', first_name = '%s', last_name = '%s', status = '%s' WHERE id = %d",
				mysql_real_escape_string($member->getUserName()),
                                mysql_real_escape_string($member->getPassword()),
                                mysql_real_escape_string($member->getEmail()),
                                mysql_real_escape_string($member->getFirstName()),
                                mysql_real_escape_string($member->getLastName()),
                                mysql_real_escape_string($member->getStatus()),
				$member->getID());
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not succesful DB problem");
		
			
	}
	
	
	public function readAll(){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, username, password, email, first_name, last_name, status FROM member;");
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$memberTemp = new Member($newArray[0],$newArray[1],$newArray[2],$newArray[3],$newArray[4],$newArray[5],$newArray[6]);
				array_push($arr, $memberTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($memberID){
		$db = new myConnection();
                $sql = sprintf("SELECT id, username, password, email, first_name, last_name, status FROM member WHERE id= %d", $memberID);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $member = new Member($db->getField("id"),$db->getField("username"),$db->getField("password"),$db->getField("email"),$db->getField("first_name"),$db->getField("last_name"),$db->getField("status"));
                mysql_close();
                return $member;         
        }
	
		public function authenticate($userName, $password){
		$db = new myConnection();
                $sql = sprintf("SELECT id FROM member WHERE username = '%s' AND password = '%s';", 
									mysql_real_escape_string($userName),
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