<?php
require_once("Connection.php");
//require_once("../Model/member_takes_test.php");
require_once(dirname(__FILE__)."\\..\\Model\\member_takes_test.php");


class MemberTakesTestMysqlDAO{
    public function write(MemberTakesTest $member_takes_test){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO member_takes_test (member_id,test_id,starting_time, ending_time, complete_flag, grade) VALUES ( %d, %d, NOW(),'01-01-1970',%d,%d);", 
								$member_takes_test->getMemberId(),
								$member_takes_test->getTestId(),
								
                        
                                $member_takes_test->getCompleteFlag(),
                                $member_takes_test->getGrade());
		
		$db->query($sql);
		$member_takes_testID = $db->getLastId();
 		mysql_close();
		
		if($member_takes_testID <= 0)
			throw new Exception("There was a problem with the database when inserting a new member takes test data");
		
		return $member_takes_testID;
		
	}
	
	public function update(MemberTakesTest $member_takes_test){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE  member_takes_test SET member_id=%d, test_id=%d , ending_time = '%s', complete_flag = %d, grade = %d WHERE id = %d",
								$member_takes_test->getMemberId(),
								$member_takes_test->getTestId(),
								mysql_real_escape_string($member_takes_test->getEndingTime()),
                                $member_takes_test->getCompleteFlag(),
                                $member_takes_test->getGrade(),
				$member_takes_test->getID());
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("Update not succesful DB problem");
		
			
	}
	
	
	public function readAll(){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id,member_id, test_id, starting_time, ending_time, complete_flag, grade  FROM member_takes_test;");
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$member_takes_testTemp = new MemberTakesTest($newArray[0],$newArray[1],$newArray[2],$newArray[3],$newArray[4],$newArray[5],$newArray[6]);
				array_push($arr, $member_takes_testTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function readAllFromMember($member_id){
			
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id,member_id, test_id, starting_time, ending_time, complete_flag, grade  FROM member_takes_test WHERE member_id = %d ORDER BY id DESC",$member_id);
			
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$member_takes_testTemp = new MemberTakesTest($newArray[0],$newArray[1],$newArray[2],$newArray[3],$newArray[4],$newArray[5],$newArray[6]);
				array_push($arr, $member_takes_testTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($member_takes_testID){
		$db = new myConnection();
                $sql = sprintf("SELECT id,member_id,test_id, starting_time, ending_time, complete_flag, grade  FROM member_takes_test WHERE id= %d", $member_takes_testID);
               	
			    $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $member_takes_test = new MemberTakesTest($db->getField("id"),
														$db->getField("member_id"),
														$db->getField("test_id"),
														$db->getField("starting_time"),
														$db->getField("ending_time"),
														$db->getField("complete_flag"),
														$db->getField("grade"));
                mysql_close();
                return $member_takes_test;         
        }
}

