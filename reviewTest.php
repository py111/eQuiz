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

	
	
	$memberTakes = new MemberTakesTest();
		try{
			$memberTakes = $memberTakesDAO->search($_GET['memberTakes']);	
			
		}catch(Exception $e){
			die($e);	
		}
	if($memberTakes->getMemberId() != $member->getID()){
		die("not authorized");	
	}
	
		$questionArray = $questionsDAO->readAllFrom($memberTakes->getTestId());
		$goodCounter=0;
		$counter=1;
		$test = $testDAO->search($memberTakes->getTestId());
		
?>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>E-Quiz</title>
<script src="./static/js/jquery-2.1.3.min.js"></script>
<script src="./static/js/script.js"></script>
</head>
<body>
<div id="header">
    	<div class="menuBoton" id="nameHolder">Welcome <?php echo $member->getFirstName()." ".$member->getLastName();?></div>
        <div class="menuBoton">E-Quiz - Review test</div>
        <div  class="menuBoton" id="logoutHolder"><a href="logout.php" style="color:#FFFFFF">Logout</a></div>
    </div>
    
    <div id="footer">
    	&copy; Daniel Rodr&iacute;guez &amp; Prachi 2015
        
    </div>
    
    
     <div id="segunda" class="seccionRest">
     <div id="notificationBar"><?php ?></div>
	 <div id="alertBar"><?php echo $error; ?></div>
    	<div class="headerInterior" style="text-align:center">
        		<input type="button" class="input-rounded-button" value="Go Back" onClick="window.location.href='myGrades.php'">
            </div>
            
         
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
	<div id="testInfo">
        	 <strong>Test information</strong><br/>
             <hr/>
             <strong>Name: </strong><?php echo $test->getName(); ?><br/>
             <strong>Grade: </strong><?php echo $memberTakes->getGrade(); ?>/100<br/>
             <strong>Starting time: </strong><?php echo $memberTakes->getStartingTime();?>
             <strong>Ending time: </strong><?php echo $memberTakes->getEndingTime();?>
             <strong>Number of questions: </strong><?php echo sizeof($questionArray);?>
             
         </div>
           	

            <div id="questionBody" style="margin-left:6%;">
            	
                
            	<?php foreach($questionArray as $question): ?>
                <div class="newBox">
							<div class="questionNumberAnswered"><?php echo $counter++; ?></div>
                            <h3 class="titleQuestion"><?php echo $question->getText();?></h3>

                        	<strong>Correct Answer:</strong>
                            <?php
								$optionC = $optionsDAO->getCorrectOptionFrom( $question->getID() );
								
								echo $optionC->getText();
							?>
                            
                        <br/>
                        
						<strong>Your answer:</strong>
                        	<?php 
						
							$cho = $chosenAnswerDAO->questionAlreadyAnswered($question->getID(),$memberTakes->getID());
							
							if($cho){
									$option = $optionsDAO->search($cho->getOptionId());
									if($option->getID() == $optionC->getID()){
										$goodCounter++;
										echo $option->getText();
										echo '<br/><img src="tick-blue.png" height="50" width="50">';
									}
									else{
										echo $option->getText();
										echo '<br/><img src="close-blue.png" height="50" width="50">';
									}
							}else{
									echo "<i>No chosen answer</i><br/>";
									echo '<img src="close-blue.png" height="50" width="50">';
							}	
							?>
		
                        </div>
                <?php endforeach;?>
                  

            </div>
       </div>
    </div>
    
    
</body>
<script type="text/javascript">
	$(document).ready(function(e) {
        var text = "<br/><strong>Good answers: </strong><?php echo $goodCounter; ?>";
		$("#testInfo").html($("#testInfo").html() + text);
    });
</script>
</html>