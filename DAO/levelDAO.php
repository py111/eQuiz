<?php
require_once("Connection.php");
//require_once("../Model/level.php");
require_once(dirname(__FILE__)."\\..\\Model\\level.php");

class LevelMysqlDAO{

	
	public function write(Level $level){
		
		$db = new myConnection();
		$sql = sprintf("INSERT INTO level (name) VALUES ( '%s');", 
				mysql_real_escape_string($level->getName()));
				$db->query($sql);
		$levelID = $db->getLastId();
		
		
 		mysql_close();

		if($levelID <= 0){
			throw new Exception("There was a problem with the database when inserting a new Level");
		}
		
		return $levelID;
		
	}
	
	public function update(Level $level){
	
		$db = new myConnection();
		$sql = sprintf("UPDATE level SET name = '%s' WHERE id = %d;",
					mysql_real_escape_string($level->getName()),
					$level->getID());
		
		$db->query($sql);
		$wasSuccesful = !$db->getError();
		mysql_close();
		if(!$wasSuccesful)
			throw new Exception("There was a problem with the database when updating the Level");
		
			
	}
	
	
	public function readAll(){
	
			$arr = array();
			$db = new myConnection();
			$sql = sprintf("SELECT id, name FROM level;");
			$db->query($sql);
			$result = $db->getQueryData();
			
			while($newArray = mysql_fetch_array($result)) {
				$levelTemp = new Level($newArray[0],$newArray[1]);
				array_push($arr, $levelTemp);	
			}
			mysql_close();
			return $arr;		
	}
	
	public function search($levelID){
		$db = new myConnection();
                $sql = sprintf("SELECT id, name  FROM level WHERE id= %d", $levelID);
                $db->query($sql);
                if($db->getNumResults()<=0)
                    return null;
                $db->nextArray();
                $level = new Level($db->getField("id"),$db->getField("name"));
                mysql_close();
                return $level;         
        }
	
}