<?php
	session_start();


	if(!isset($_SESSION['id_member'])){
		header("Location: memberLogin.php");
		return;
	}
	
	require_once("./Model/member.php");
	require_once("./DAO/memberDAO.php");
	require_once("./DAO/testDAO.php");
	require_once("./DAO/levelDAO.php");
	require_once("./DAO/quizMakerDAO.php");
	
	$memberDAO = new MemberMysqlDAO();
	$testDAO = new TestMysqlDAO();
	$levelDAO = new LevelMysqlDAO();
	$quizMakerDAO = new QuizMakerMysqlDAO();
	
	$levelArray = $levelDAO->readAll();
	$testArray = $testDAO->readAll();
	
	
	$member = $memberDAO->search($_SESSION['id_member']);
	//$member = new Member();		

	
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
	        </div>
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
       
       		<a href="myGrades.php"><input type="button" class="input-rounded-button" value="View my grades"/></a>
            
			<?php	if( empty($testArray) ):	?>
            	<h2>No tests available</h2>
            <?php endif;?>
            
            <?php	if( !empty($testArray) ):	?>
            	<table align="center" width="60%">
                	<tr>
                    	<th>Level</th>
                        <th>Name</th>
                        <th>Date Created</th>
                        <th>Created by</th>
                        <th>Do test</th>
                    </tr>
                <?php foreach($testArray as $test):?>
                	<tr>
                    	<td class="level-<?php echo $test->getLevelId();?>"><?php echo $levelArray[$test->getLevelId()-1]->getName(); ?></td>
                        <td><?php echo $test->getName();?></td>
                        <td><?php echo $test->getCreateTime();?></td>
                        <td><?php echo $quizMakerDAO->search($test->getQuizMakerId())->getUserName();?></td>
                        <td><a href="./takeTest.php?id=<?php echo $test->getID(); ?>">Do test</a></td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            
            
             
       </div>
    </div>
    
    
</body>
</html>