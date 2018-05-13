<?php

//require_once("../Model/questions.php");
require_once("../DAO/questionsDAO.php");
require_once("../DAO/memberTakesTestDAO.php");
require_once("../DAO/chosen_answerDAO.php");


require_once("../functions.php");
session_start();

validateLogin($_SESSION['user_logged_in']);

$questionsDAO = new QuestionsMysqlDAO();
$questions = $questionsDAO->readAllFromTest($_GET['testID']);

$chosenAnswerDAO = new ChosenAnswerMysqlDAO();

$questions = array();


foreach($questions as $questionTemp){

	$singleQuestionArray = array("id" => $questionTemp->getID(),
							  "text" => substr($questionTemp->getText(),0,15),
							  "status" => $questionTemp->getStatus(),
							  "test_id" => $questionTemp->getTestId());
							  
	
	array_push($questions,$singleQuestionArray);
		
}

	header('Content-Type: application/json');
	echo json_encode($questions);
