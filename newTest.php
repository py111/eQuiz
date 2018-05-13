<?php
	session_start();


	if(!isset($_SESSION['id_quiz_maker'])){
		header("Location: makerLogin.php");
		return;
	}
	
	require_once("./DAO/testDAO.php");
	require_once("./DAO/levelDAO.php");
	require_once("./DAO/quizMakerDAO.php");
	
	$levelDAO = new LevelMysqlDAO();
	$quizMakerDAO = new QuizMakerMysqlDAO();
	$levelArray = $levelDAO->readAll();
	$quizMaker = $quizMakerDAO->search($_SESSION['id_quiz_maker']);

	
?>

<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>E-Quiz</title>
<script src="./static/js/jquery-2.1.3.min.js"></script>
<script src="./static/js/script.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
	  if ( $("#alertBar").html() ){
		 alertUser($("#alertBar").html());
	  }
	});

</script>
</head>
<body>
<div id="header">
    	<div class="menuBoton" id="nameHolder">Welcome <?php echo $quizMaker->getFirstName()." ".$quizMaker->getLastName();?></div>
        <div class="menuBoton">E-Quiz - My tests</div>
        <div  class="menuBoton" id="logoutHolder"><a href="logout.php" style="color:#FFFFFF">Logout</a></div>
    </div>
    
    <div id="footer">
    	&copy; Daniel Rodr&iacute;guez &amp; Prachi 2015
        
    </div>
    
    
     <div id="segunda" class="seccionRest">
     <div id="notificationBar"><?php ?></div>
	 <div id="alertBar"><?php echo $error; ?></div>
    	<div class="headerInterior" style="text-align:center">
	        </div>
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
       
       		<a href="newTest.php"><input type="button" class="input-rounded-button" value="Create new test"/></a>
            

            
             
       </div>
    </div>
    
    
</body>
</html>