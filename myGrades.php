<?php
	session_start();
	
	

	if(!isset($_SESSION['id_member'])){
		header("Location: memberLogin.php");
		return;
	}
	$messageToUser= "";
	if(isset( $_GET['resume'] ) ){
		$messageToUser = "Your exam was saved, you can finish it by clicking the resume button";
	}
	if(isset($_GET['stat'])){
		
		switch($_GET['stat']){
			case "successful":
				$messageToUser = "Your test has been graded, please find your result below";	
		}
		
	}
	
	require_once("./Model/member.php");
	require_once("./DAO/memberDAO.php");
	require_once("./DAO/memberTakesTestDAO.php");
	require_once("./DAO/testDAO.php");
	require_once("./DAO/levelDAO.php");
	
	$memberDAO = new MemberMysqlDAO();
	$testDAO = new TestMysqlDAO();
	$levelDAO = new LevelMysqlDAO();
	$memberTakesDAO = new MemberTakesTestMysqlDAO();
	
	$member = $memberDAO->search($_SESSION['id_member']);
	$levelArray = $levelDAO->readAll();
	
	$memberTakesArray = $memberTakesDAO->readAllFromMember($member->getId());
	
	
	
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
	   if ( $("#notificationBar").html() ){
		 notifyUser($("#notificationBar").html());
	  }
	  
	});

</script>
</head>
<body>
<div id="header">
    	<div class="menuBoton" id="nameHolder">Welcome <?php echo $member->getFirstName()." ".$member->getLastName();?></div>
        <div class="menuBoton">E-Quiz - My Grades</div>
        <div  class="menuBoton" id="logoutHolder"><a href="logout.php" style="color:#FFFFFF">Logout</a></div>
    </div>
    
    <div id="footer">
    	&copy; Daniel Rodr&iacute;guez &amp; Prachi 2015
        
    </div>
    
     <div id="segunda" class="seccionRest">
     <div id="notificationBar"><?php echo $messageToUser;?></div>
	 <div id="alertBar"><?php echo $error; ?></div>
    	<div class="headerInterior" style="text-align:center">
	        </div>
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
       
       		<a href="memberArea.php"><input type="button" class="input-rounded-button" value="List of tests"/></a>
            
			<?php	if( empty($memberTakesArray) ):	?>
            	<h2>No records available</h2>
            <?php endif;?>
            
            <?php	if( !empty($memberTakesArray) ):	?>
            	<table align="center" width="60%">
                	<tr>
                    	<th>Level</th>
                        <th>Name</th>
                        <th>Time Taken</th>
                        <th>Time Finished</th>
                        <th>Grade</th>
                        <th>Review</th>
                    </tr>
                <?php foreach($memberTakesArray as $memberTakes):?>
                	<?php $test = $testDAO->search($memberTakes->getTestId());?>
                    <?php if( isset($_GET["idTest"]) && ($memberTakes->getID() == $_GET["idTest"] ) ):?>
                		<tr class="highlight">
					<?php endif;?>
                    <?php if ( !isset($_GET["idTest"]) ): ?>
                    	<tr>
                    <?php endif;?>
                    
                    	<td class="level-<?php echo $test->getLevelId();?>"><?php echo $levelArray[$test->getLevelId()-1]->getName(); ?></td>
                        <td><?php echo $test->getName();?></td>
                        <td><?php echo $memberTakes->getStartingTime();?></td>
                        <td><?php echo  $memberTakes->getCompleteFlag()  ? $memberTakes->getEndingTime():  "Not completed"; ?></td>
                        <td><?php echo  $memberTakes->getCompleteFlag()  ? $memberTakes->getGrade():  "Not completed"; ?></td>
                        <td>
							<?php if( $memberTakes->getCompleteFlag()  ):?>
                        		<a href="./reviewTest.php?memberTakes=<?php echo $memberTakes->getID(); ?>">Review</a>
                            <?php endif;?>
                            <?php if(  !$memberTakes->getCompleteFlag() ):?>
                        		<a href="./takeTest.php?id=<?php echo $test->getID(); ?>&memberTakes=<?php echo $memberTakes->getID();?>">Resume</a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            
            
             
       </div>
    </div>
    
    
</body>
</html>