<?php
header('Content-Type: application/json');


require_once("../DAO/questionsDAO.php");
require_once("../DAO/chosen_answerDAO.php");


require_once("../functions.php");
session_start();

validateLogin($_SESSION['user_logged_in']);

$questionID = $_POST['questionid'];
$optionID = $_POST['optionid'];
$memberTakesTestID = $_POST['testnumber'];

$chosenAnswerDAO = new ChosenAnswerMysqlDAO();

try{

	$chosenAnswer = $chosenAnswerDAO->searchByQuestionMemberTakesTestID($questionID,$memberTakesTestID);
	$chosenAnswer->setOptionId($optionID);
	$chosenAnswerDAO->update($chosenAnswer);

	$status = array("error"=>0);
	echo json_encode($status);
	
}catch(Exception $e){
	$status = array("error"=>1, "message"=> $e->getMessage());
	echo json_encode($status);
	
}