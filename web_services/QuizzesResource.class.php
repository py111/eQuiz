<?php

require_once("EquizResource.class.php");
require_once("../DAO/testDAO.php");
require_once("../Model/test.php");

require_once("../DAO/questionsDAO.php");
require_once("../Model/questions.php");

require_once("../DAO/optionDAO.php");
require_once("../Model/options.php");

	require_once("../Scanner/parser.php");
	require_once("../Scanner/compiler.php");


class QuizzesResource extends EquizResource{
	
	function do_post(){
		session_start();
		if( !isset($_SESSION['id_quiz_maker'])){
			 $this->exit_error(401, "");
		}
		$idQuizMaker = $_SESSION['id_quiz_maker'];
		
		if( empty($_POST['name']) || empty($_POST['level_id']) || empty($_FILES['testfile']['tmp_name'])){
			$this->exit_error(400, "No Name Level id or CSV file");
		}
		
		$nameTest = $_POST['name'];
		$level_id = $_POST['level_id'];
		
		try{
			$textOftheCSV = file_get_contents($_FILES['testfile']['tmp_name']);
			
			$parser = new Parser($textOftheCSV);
			$arr = $parser->getArray();
			//print_r($arr);
			$scanner = new Compiler($arr);
			$testNode = $scanner->run();
			
			
			$testDAO = new TestMysqlDAO();
			$test = new Test();
			$test->setName($nameTest);
			$test->setQuizMakerId($idQuizMaker);
			$test->setLevelId($level_id);
			$test->setStatus(1);
			$test->setHash("");
			$test->setStartingTime("01-01-1970");
			$test->setEndingTime("01-01-2030");
			
			$idNewTest = $testDAO->write($test);
			
			$questionDAO = new QuestionsMysqlDAO();
			$optionDAO = new OptionsMysqlDAO();
			foreach($testNode->getChilds() as $questionNode){
				$question = new Question();
				$question->setText($questionNode->getText());
				$question->setTestId($idNewTest);
				$question->setStatus(1);	
				
				$idNewQuestion = $questionDAO->write($question);
				foreach($questionNode->getChilds() as $optionNode){
					$option = new Options();
					$option->setText($optionNode->getText());
					$option->setQuesitionsId($idNewQuestion);
					if($optionNode->iAmCorrect()){
						$option->setCorrectFlag("1");	
					}else{
						$option->setCorrectFlag("0");	

					}
					$optionDAO->write($option);
				}
				
			}
			$this->statusCode=200;

			array_push($this->headers,"Location: ../makerArea.php?stat=create&id=".$idNewTest);
			
		}catch(Exception $e){
			 $this->exit_error(500, $e->getMessage());

		}
	
	}
	
	function do_get() {
	
		if(! $this->is_member() ){
			 $this->exit_error(401, "");
			 die();	
		}
	
		try {
		  $testDAO = new TestMysqlDAO();
		  $testArray = $testDAO->readAll();
		  
		  $JSONArray = array();
		  
		  foreach($testArray as $testTemp){
			  
				$singleTestArray = array("id" => $testTemp->getID(),
								  "create_time" => $testTemp->getCreateTime(),
								  "name" => $testTemp->getName(),
								  "status" => $testTemp->getStatus(),
								  "starting_time" => $testTemp->getStartingTime(),
								  "ending_time" => $testTemp->getEndingTime(),
								  "level_id", $testTemp->getLevelId(),
								  "quiz_maker_id",$testTemp->getQuizMakerId()
								  );
				array_push($JSONArray,$singleTestArray);
								  
		  }
			header('Content-Type: application/json');
			$this->statusCode = 200;
			$this->headers[] = "Content-type: application/json; charset=utf-8";
	
		  
		} 
		catch (PDOException $e) {
		  $this->exit_error(500, $e->getMessage());
		}
		
	}

	
}

QuizzesResource::run();