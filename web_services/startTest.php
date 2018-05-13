<?php

header('Content-Type: application/json');


//require_once("../DAO/testDAO.php");
require_once("../DAO/questionsDAO.php");
require_once("../DAO/memberTakesTestDAO.php");
require_once("../DAO/chosen_answerDAO.php");

//require_once("../Model/test.php");
require_once("../Model/questions.php");
require_once("../Model/member_takes_test.php");
require_once("../Model/chosen_answers.php");

require_once("../functions.php");

session_start();
validateLogin($_SESSION['user_logged_in']);

$testID = $_GET['test_id'];


//Initialize the test
$userMakesTestDAO = new MemberTakesTestMysqlDAO();

	$membetTakesTestTemp = new MemberTakesTest();
	$membetTakesTestTemp->setMemberId($_SESSION['member_id']);
	$membetTakesTestTemp->setTestId($testID);
	$membetTakesTestTemp->startTest();
try{	
	$memberTakesTestID = $userMakesTestDAO->write($membetTakesTestTemp);
	//-------------------------------
	
	$questionsDAO = new QuestionsMysqlDAO();
	$choseAnswerDAO = new ChosenAnswerMysqlDAO();
	
	$questions = $questionsDAO->readAllFromTest($testID);
	
	
	foreach($questions as $question){
		
		$chosenAnswerTemp = new ChosenAnswer();
		$chosenAnswerTemp->setMemberTakesTestId($memberTakesTestID);
		$chosenAnswerTemp->setQuestionID($question->getID());
		$choseAnswerDAO->write($chosenAnswerTemp);
	
	}
	$status = array("error"=>0);
	echo json_encode($status);

}catch(Exception $e){
	$status = array("error"=>1,"message"=>$e->getMessage());
	echo json_encode($status);
}

