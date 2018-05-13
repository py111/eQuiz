<?php
	session_start();


	require_once("./DAO/testDAO.php");
	require_once("./DAO/questionsDAO.php");
	require_once("./DAO/optionDAO.php");

	
		
	$testDAO = new TestMysqlDAO();
	$questionsDAO = new QuestionsMysqlDAO();
	$optionsDAO = new OptionsMysqlDAO();
	
	if(!isset($_GET['id'])){
		die("No tests found");
	}
	$testID = $_GET['id'];

	$test = $testDAO->search($testID);
	if($test == null){
		die("No test found");	
	}
		$questionArray = $questionsDAO->readAllFrom($test->getID());
		$questionsJSON = array();
		foreach($questionArray as $question){
			$optionsJSON = array();
			$optionsArray = $optionsDAO->readAllFromQuestion($question->getID());
			foreach($optionsArray as $option){
				$optionJSON = array("id" => $option->getID(),
									"text" => $option->getText(),
									"correct" => $option->getCorrectFlag());
				array_push($optionsJSON,$optionJSON);	
			}

			$qustionJSON = 		 array("id" => $question->getID(),
									   "text" => $question->getText(),
									   "options" => $optionsJSON);

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
	
	var grade=0;
	
	function finishPublic(){
		counter=0;
		$("#questionBody").html("");
		var question = $(document.createElement('div'));	
				question.attr("class","newBox");
				var title = $(document.createElement('h3'));	
					title.html("<?php echo $test->getName(); ?>");
					title.attr("class","titleQuestion");
					title.attr("style","margin-left:0");

				question.append(title);
				
				var grades = $(document.createElement('p'));
					var label = $(document.createElement('strong')).text("Your Grade: ");
					question.append(label);
					
					decimalGrade = (grade/questions.length)*100;
					
					intGrade = Math.floor(decimalGrade)
					
					grades.text(intGrade+"/100");
				question.append(grades);	
				
				
					buttonPrev = $(document.createElement("input"));
					buttonPrev.attr("class","input-rounded-button");
					buttonPrev.attr("type","button");
					buttonPrev.attr("value","Retake Test");
					buttonPrev.attr("onClick","location.reload();");
					question.append(buttonPrev);
				
				<?php if(isset($_GET['comingfromlist'])): ?>
					backLink = $(document.createElement("a"));
						backLink.attr("href","newPublicUserTest.php");
						
						backButton = $(document.createElement("input"));
						backButton.attr("type","button");
						backButton.attr("class","input-rounded-button");
						backButton.attr("value","Go back to list");
						
					backLink.append(backButton);
					$("#questionBody").append(backLink);
					
				<?php endif;?>
					
					
				
				$("#questionBody").append(question);
	}
	
	
	function goToQuestion(questionIndex){
		var question = questions[questionIndex];
		$("#questionBody").html("");
		 
		 if(questions[questionIndex+1]){
			     createPublicQuestion(questionIndex,question['text'], question['options'], (questionIndex+1));
		   }else{
				 createPublicQuestion(questionIndex,question['text'], question['options'],0);
		   }
	}
	var counter = 15*60;

	function gradeAnswer(correct,next){
		grade+=correct;
		
		if(next){
			goToQuestion(next);	
		}else{
			finishPublic();	
		}
	}
	
	function startTimer( ){
		counter--;
		if(counter<0) counter=0;
		displayTime();
		if(counter <=0){
			finishPublic();
		}else{
			setTimeout("startTimer()", 1000);
		}
	}
	function displayTime(){
		hours = Math.floor(counter/3600);
		
		minutes = Math.floor(   (counter/60)-(hours*60)   );

		if(hours < 10)hours = "0"+hours;
		
		
		if(minutes < 10) minutes = "0"+minutes;
		seconds = (counter % 60);
		if(seconds < 10) seconds = "0"+seconds;
		
		$("#timer").text("Time remaining: "+hours+":"+minutes+":"+seconds);
	}
	
	$( document ).ready(function() {
	  if ( $("#alertBar").html() ){
		 alertUser($("#alertBar").html());
	  }
	  goToQuestion(0);	
	  startTimer();
       
	});
	
	
	
	
</script>
</head>
<body>
<div id="header">
    	<div class="menuBoton" id="nameHolder">Welcome Guest</div>
        <div class="menuBoton">E-Quiz - <?php echo $test->getName(); ?></div>
        <div  class="menuBoton" id="logoutHolder"></div>
    </div>
    
    <div id="footer">
    	&copy; Daniel Rodr&iacute;guez &amp; Prachi 2015
        
    </div>
    
    
     <div id="segunda" class="seccionRest">
     <div id="notificationBar"><?php ?></div>
	 <div id="alertBar"><?php echo $error; ?></div>
    	<div class="headerInterior" style="text-align:center">
        	<div id="timer"></div>
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