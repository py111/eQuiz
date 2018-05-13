<?php

	session_start();
	
	if(!isset($_SESSION['id_member'])){
		header("Location: memberLogin.php");
		return;
	}
	
	require_once("./DAO/memberTakesTestDAO.php");
	require_once("./DAO/chosen_answerDAO.php");
	require_once("./DAO/memberDAO.php");
	require_once("./DAO/optionDAO.php");
	require_once("./DAO/questionsDAO.php");

	
	$idMember = $_SESSION['id_member'];
	$memberTakesTestID = $_GET['memberTakesID'];
	
	$memberTakesDAO = new MemberTakesTestMysqlDAO();
	$chosenAnswerDAO = new ChosenAnswerMysqlDAO();
	$memberDAO = new MemberMysqlDAO();
	$optionDAO = new OptionsMysqlDAO();
	$questionDAO = new QuestionsMysqlDAO();
	
	
	$memberTakes = $memberTakesDAO->search($memberTakesTestID);
	if($memberTakes == null){
		die("Something went wrong");	
	}
	
	if ( $memberTakes->getMemberId() != $idMember ){
		die("Not authorized");	
	}
	
	if( $memberTakes->getCompleteFlag() ){
		die("Already completed");	
	}
	
	$numberOfQuestion = $questionDAO->getNumberQuestionsFromTest($memberTakes->getTestId());
	
	$chosenAnswerArray = $chosenAnswerDAO->getChosenAnswerFromMemberTakesTest($memberTakes->getID());
	
	$correctAnswers=0;
	foreach($chosenAnswerArray as $chosenAnswer){
		
		$optionTemp = $optionDAO->search($chosenAnswer->getOptionId());
		if( $optionTemp->getCorrectFlag() ){
				$correctAnswers++;
		}
	}
	$score = ($correctAnswers/$numberOfQuestion)*100;
	
	$memberTakes->setGrade($score);
	$memberTakes->endTest();
	
	try{
		$memberTakesDAO->update($memberTakes);
		header("Location: myGrades.php?stat=successful&idTest=".$memberTakes->getID());
		
	}catch(Exception $e){
		header("Location:  myGrades.php?stat=error&r=finish");
	}
	
	
	
	
