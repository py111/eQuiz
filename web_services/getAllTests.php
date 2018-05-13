<?php

//require_once("../Model/test.php");
require_once("../DAO/testDAO.php");
require_once("../functions.php");
session_start();

validateLogin($_SESSION['user_logged_in']);

$testDAO = new TestMysqlDAO();

$tests = $testDAO->readAll();

$tests = array();


foreach($tests as $testTemp){
	
	$singleTestArray = array("id" => $testTemp->getID(),
							  "create_time" => $testTemp->getCreateTime(),
							  "name" => $testTemp->getName(),
							  "status" => $testTemp->getStatus(),
							  "starting_time" => $testTemp->getStartingTime(),
							  "ending_time" => $testTemp->getEndingTime(),
							  "level_id", $testTemp->getLevelId(),
							  "quiz_maker_id",$testTemp->getQuizMakerId());
	
	array_push($tests,$singleTestArray);
		
}

	header('Content-Type: application/json');
	$this->body =  json_encode($tests);
