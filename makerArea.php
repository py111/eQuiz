<?php
	session_start();


	if(!isset($_SESSION['id_quiz_maker'])){
		header("Location: makerLogin.php");
		return;
	}
	
	$messageToUser="";
	if(isset($_GET['stat'])){
		switch($_GET['stat']){
			case "create":
				$messageToUser = "Test created succesfully";
				break;	
		}
	}
	
	require_once("./DAO/testDAO.php");
	require_once("./DAO/levelDAO.php");
	require_once("./DAO/quizMakerDAO.php");
	
	$testDAO = new TestMysqlDAO();
	$levelDAO = new LevelMysqlDAO();
	$quizMakerDAO = new QuizMakerMysqlDAO();
	$levelArray = $levelDAO->readAll();
	$testArray = $testDAO->readAllFrom($_SESSION['id_quiz_maker']);
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
	   if ( $("#notificationBar").html() ){
		 notifyUser($("#notificationBar").html());
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
     <div id="notificationBar"><?php echo $messageToUser; ?></div>
	 <div id="alertBar"><?php echo $error; ?></div>
    	<div class="headerInterior" style="text-align:center">
	        </div>
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
       
       		<input type="button" class="input-rounded-button" value="Create new test" onClick='$("#formNewTest").slideToggle();'/>
            
            <div id="formNewTest" style="display:none">
            	<form method="post" action="./web_services/QuizzesResource.class.php" enctype="multipart/form-data"/>
            
            	<select name="level_id">
                	<?php foreach($levelArray as $level):?>
                    	<option value="<?php echo $level->getID();?>"><?php echo $level->getName(); ?></option>
                    <?php endforeach;?>
                </select>
                
                <input type="text" name="name" value="" placeholder="Name of the test"/>
                
                <input type="file" name="testfile" accept=".csv" >
                
                <input type="submit" value="Create">
            
            </form>
            
            </div>
            
			<?php	if( empty($testArray) ):	?>
            	<h2>No tests available</h2>
            <?php endif;?>
            
            <?php	if( !empty($testArray) ):	?>
            	<table align="center" width="60%">
                	<tr>
                    	<th>Level</th>
                        <th>Name</th>
                        <th>Date Created</th>
                        <th>Public link</th>
                    </tr>
                <?php foreach($testArray as $test):?>
                	<?php if(isset($_GET['id']) && $_GET['id'] ==$test->getID()): ?>
                		<tr class="highlight">
                    <?php endif; ?>
                    <?php if(!isset($_GET['id']) ): ?>
                		<tr>
                    <?php endif; ?>
                    	<td class="level-<?php echo $test->getLevelId();?>"><?php echo $levelArray[$test->getLevelId()-1]->getName(); ?></td>
                        <td><?php echo $test->getName();?></td>
                        <td><?php echo $test->getCreateTime();?></td>
                        <td>http://localhost/equiz/publicTest.php?id=<?php echo $test->getID(); ?></td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            
            
             
       </div>
    </div>
    
    
</body>
</html>