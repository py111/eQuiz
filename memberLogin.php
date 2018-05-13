<?php
	$error="";
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		
		if( !isset($_POST['user']) || !isset($_POST['password']) ){
			$error ="Missing fields for the login";
		}
		require_once("./DAO/memberDAO.php");
		
		$memberDAO = new MemberMysqlDAO();
		$idMember = $memberDAO->authenticate($_POST['user'],$_POST['password']);
		
		if($idMember <=0){
			$error ="Username/password not found";
		}else{
			session_start();
			$_SESSION['id_member'] = $idMember;
			header("Location: memberArea.php");
			return;	
		}
		
	}
	
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
    	<div class="menuBoton" id="nameHolder"><a href="index.php" style="color:#FFFFFF">Go Back</a></div>
        <div class="menuBoton">E-Quiz - Member Login</div>
        <div  class="menuBoton" id="logoutHolder"></div>
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
       
       		<form method="post" action="memberLogin.php">
                <input type="text" name="user" placeholder="User..." class="rounded" required  id="user">
                <br>
                <input type="password" name="password" placeholder="Password" class="rounded" required id="password">
                <br>
                 <input class="input-rounded-button" type="submit" value="Login" id="submit"/>
            </form>	
             
       </div>
    </div>
    
    
</body>
</html>