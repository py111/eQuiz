<?php
	session_start();


	if(!isset($_SESSION['id_member'])){
		header("Location: memberLogin.php");
		return;
	}
	
	require_once("./Model/member_takes_test.php");
	require_once("./DAO/memberDAO.php");
	require_once("./DAO/testDAO.php");
	require_once("./DAO/memberTakesTestDAO.php");
	require_once("./DAO/questionsDAO.php");
	require_once("./DAO/optionDAO.php");
	require_once("./DAO/chosen_answerDAO.php");

	
		
	$memberDAO = new MemberMysqlDAO();
	$testDAO = new TestMysqlDAO();
	$questionsDAO = new QuestionsMysqlDAO();
	$optionsDAO = new OptionsMysqlDAO();
	$memberTakesDAO = new MemberTakesTestMysqlDAO();
	$chosenAnswerDAO = new ChosenAnswerMysqlDAO();
	
	$member = $memberDAO->search($_SESSION['id_member']);

	$testID = $_GET['id'];	
	$test = $testDAO->search($testID);
	
	$memberTakes = new MemberTakesTest();
	if(!isset($_GET['memberTakes'])){
		$memberTakes = new MemberTakesTest();
		$memberTakes->setTestId($test->getID());
		$memberTakes->setMemberId($member->getID());
		
		try{
			$idM = $memberTakesDAO->write($memberTakes);
			$memberTakes->setID($idM);
		}catch(Exception $e){
			die($e);	
		}
		
	}else{
		try{
			$memberTakes = $memberTakesDAO->search($_GET['memberTakes']);	
		
		}catch(Exception $e){
			die($e);	
		}
	}
		$questionArray = $questionsDAO->readAllFrom($test->getID());
		$questionsJSON = array();
		foreach($questionArray as $question){
			$optionsJSON = array();
			$optionsArray = $optionsDAO->readAllFromQuestion($question->getID());
			foreach($optionsArray as $option){
				$optionJSON = array("id" => $option->getID(),
									"text" => $option->getText());
				array_push($optionsJSON,$optionJSON);	
			}
				$qustionJSON = array();
				if(!isset($_GET['memberTakes'])){
				$qustionJSON =  array("id" => $question->getID(),
									   "text" => $question->getText(),
									   "options" => $optionsJSON,
									   "chosen_answer" => 0,
									   "chosen_answer_dbID" => 0);
				}else{
					$cho =  $chosenAnswerDAO->questionAlreadyAnswered( $question->getID(),$memberTakes->getID());
				if( $cho == 0){
					$qustionJSON =  array("id" => $question->getID(),
									   "text" => $question->getText(),
									   "options" => $optionsJSON,
									   "chosen_answer" => 0,
									   "chosen_answer_dbID" => 0);
				}else{
					$qustionJSON =  array("id" => $question->getID(),
									   "text" => $question->getText(),
									   "options" => $optionsJSON,
									   "chosen_answer" => $cho->getOptionId(),
									   "chosen_answer_dbID" => $cho->getID());
				}
				
				}
			array_push($questionsJSON,$qustionJSON);
			
		}
		
?>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>E-Quiz</title>
<script src="./static/js/jquery-2.1.3.min.js"></script>
<script src="./static/js/script.js"></script>
<script type="text/javascript">
	var questions = <?php echo json_encode($questionsJSON); ?>;
	
	function finishTest(){
		
		window.location.href = "finishTest.php?memberTakesID=<?php echo $memberTakes->getID() ?>";
			
	}
	
	
	function createTableQuestions(){
		$("#questionTable").html("");
		var questionList = $(document.createElement("ul"));
		questionList.attr("id","listQuestion");
		var option="";
		var optionLink="";
		$.each(questions,function(index,question){
			option = $(document.createElement("li"));
			option.attr("onclick","javascript:goToQuestion("+index+");");
			
			optionLink = $(document.createElement("a"));
			optionLink.attr("href","#");
			optionLink.text(index+1);
			
			if(question["chosen_answer"]){
				optionLink.attr("class","answered");	
			}else{
				optionLink.attr("class","not_answered");
			}
			option.append(optionLink);
			questionList.append(option);
		});
		$("#questionTable").append(questionList);
	}
	
	function goToQuestion(questionIndex){
		var question = questions[questionIndex];
		$("#questionBody").html("");
		 
		 if(questions[questionIndex+1]){
			  if(questions[(questionIndex-1)])
          	 	 createQuestion(questionIndex,question['text'], question['options'], question['chosen_answer'],(questionIndex+1),(questionIndex-1),question['chosen_answer_dbID'],<?php echo $memberTakes->getID() ?>);
		   	  else
			     createQuestion(questionIndex,question['text'], question['options'], question['chosen_answer'],(questionIndex+1),-1,question['chosen_answer_dbID'],<?php echo $memberTakes->getID() ?>);
		   }else{
			   if(questions[(questionIndex-1)]){	
			      createQuestion(questionIndex,question['text'], question['options'], question['chosen_answer'],0,(questionIndex-1),question['chosen_answer_dbID'],<?php echo $memberTakes->getID() ?>);
			   }
				else
				 createQuestion(questionIndex,question['text'], question['options'], question['chosen_answer'],0,-1,question['chosen_answer_dbID'],<?php echo $memberTakes->getID() ?>);
		   }
	}
	
	$( document ).ready(function() {
	  if ( $("#alertBar").html() ){
		 alertUser($("#alertBar").html());
	  }
	  createTableQuestions();
	  goToQuestion(0);	
       
	});

</script>
</head>
<body>
<div id="header">
    	<div class="menuBoton" id="nameHolder">Welcome <?php echo $member->getFirstName()." ".$member->getLastName();?></div>
        <div class="menuBoton">E-Quiz - List of tests</div>
        <div  class="menuBoton" id="logoutHolder"><a href="logout.php" style="color:#FFFFFF">Logout</a></div>
    </div>
    
    <div id="footer">
    	&copy; Daniel Rodr&iacute;guez &amp; Prachi 2015
        
    </div>
    
    
     <div id="segunda" class="seccionRest">
     <div id="notificationBar"><?php ?></div>
	 <div id="alertBar"><?php echo $error; ?></div>
    	<div class="headerInterior" style="text-align:center">
        		<input type="button" class="input-rounded-button" value="Save&amp;Exit" onClick="window.location.href='myGrades.php?resume=1&idTest=<?php echo $memberTakes->getID();?>'">
	        	<input class="input-rounded-button" type="button" value="Finish" onClick="finishTest();">
            </div>
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
			<div id="questionTable">
            
            </div>
            
            <div id="questionBody">
            	
            </div>
       </div>
    </div>
    
    
</body>
</html>