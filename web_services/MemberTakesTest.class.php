<?php

require_once("EquizResource.class.php");

require_once("../DAO/chosen_answerDAO.php");
require_once("../DAO/memberTakesTestDAO.php");

require_once("../Model/chosen_answers.php");

//require_once(dirname(__FILE__)."\\..\\DAO\\chosen_answerDAO.php");

class MemberTakesTestService extends EquizResource{
	
	function do_post(){
		
		if(!$this->is_member()){
			$this->exit_error(300, "NotAuthorized");
		}
		
		if( empty($_POST['member_takes']) || empty($_POST['option_id']) ){
			$this->exit_error(400, "NOMEMBERTAKESTESTOROPTION");
		}
		

		try{
			$chosenAnswerDAO = new ChosenAnswerMysqlDAO();
			$memberTakesTestDAO = new MemberTakesTestMysqlDAO();
			$memberTakes = $memberTakesTestDAO->search($_POST['member_takes']);
			
			if($memberTakes->getMemberId() != $_SESSION['id_member']){
				$this->exit_error(300, "NotAuthorized");	
			}
			
			if($memberTakes->getCompleteFlag()){
				$this->exit_error(300, "TestAlreadyGraded");
			}
			
			$chosenAnswer = new ChosenAnswer();
			$chosenAnswer->setOptionId($_POST['option_id']);
			$chosenAnswer->setMemberTakesTestId($_POST['member_takes']);
			
			$chosenAnswerID = $chosenAnswerDAO->write($chosenAnswer);
			$this->statusCode = 200;
            $this->body = $chosenAnswerID;	
		  
		}catch(Exception $e){
			$this->exit_error(500, $e->getMessage());	
		}	
	}
	
	function do_put(){
		
		if(!$this->is_member()){
			$this->exit_error(300, "NotAuthorized");
		}
		parse_str(file_get_contents("php://input"), $_PUT);
		if( empty($_PUT['chosen_answer_id']) || empty($_PUT['option_id']) ){
			$this->exit_error(400, "NOCHOSENANSWEROROPTION");
		}else{
			try{
				$chosenAnswerDAO = new ChosenAnswerMysqlDAO();
				$chosenAnswer = $chosenAnswerDAO->search($_PUT['chosen_answer_id']);
				
				$memberTakesDAO = new MemberTakesTestMysqlDAO();
				$memberTakes = $memberTakesDAO->search($chosenAnswer->getMemberTakesId());
				if($memberTakes->getMemberId() != $_SESSION['id_member']){
					$this->exit_error(300, "NotAuthorized");	
				}
				
				if($memberTakes->getCompleteFlag()){
					$this->exit_error(300, "TestAlreadyGraded");
				}
				
				$chosenAnswer->setOptionId($_PUT['option_id']);
				$chosenAnswerDAO->update($chosenAnswer);	
			}catch(Exception $e){
				$this->exit_error(500, $e->getMessage());	
			}
		  $this->statusCode = 204;
          $this->body = "";	
		}
	}
	
}

MemberTakesTestService::run();