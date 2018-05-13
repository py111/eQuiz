<?php

header('Content-Type: application/json');


//require_once("../DAO/testDAO.php");
require_once("../DAO/questionsDAO.php");
require_once("../DAO/memberTakesTestDAO.php");
require_once("../DAO/chosen_answerDAO.php");
require_once("../DAO/optionDAO.php");

//require_once("../Model/test.php");
require_once("../Model/questions.php");
require_once("../Model/options.php");
require_once("../Model/member_takes_test.php");
require_once("../Model/chosen_answers.php");

require_once("../functions.php");

session_start();
validateLogin($_SESSION['user_logged_in']);

$testID = $_GET['test_id'];
$memberTakesTestID = $_GET['testnumber'];

	
try{	

	$userMakesTestDAO = new MemberTakesTestMysqlDAO();
	$membetTakesTestTemp = $userMakesTestDAO->search($memberTakesTestID);
	
	$questionsDAO = new QuestionsMysqlDAO();
	$choseAnswerDAO = new ChosenAnswerMysqlDAO();
	$optionsDAO = new OptionsMysqlDAO();
	
	$questions = $questionsDAO->readAllFromTest($testID);
	
	$grade = 0;
	$goodAnswers = 0;
	foreach($questions as $question){
		$optionTemp = new Options();
		$chosenAnswerTemp = $choseAnswerDAO->searchByQuestionMemberTakesTestID($question->getID(),$memberTakesTestID);
		$optionTemp2 = $optionsDAO->search($chosenAnswerTemp->getOptionId());
		
		if($optionTemp2->getCorrectFlag()) $goodAnswers++;
		$chosenAnswerTemp->setFinalFlagTrue();
		
		$choseAnswerDAO->update($chosenAnswerTemp);
	}
	
	$grade = ($goodAnswers/sizeof($questions))*100;
	$membetTakesTestTemp->setGrade($grade);
	$membetTakesTestTemp->setAsCompleted();
	
	$userMakesTestDAO->update($membetTakesTestTemp);
	
	
	$status = array("error"=>0);
	echo json_encode($status);

}catch(Exception $e){
	$status = array("error"=>1,"message"=>$e->getMessage());
	echo json_encode($status);
}

