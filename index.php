<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>E-Quiz</title>
<script src="./jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="scripts.js"></script>
</head>
<body>
<div id="header">
		<div class="menuBoton" id="nameHolder"></div>
        <div class="menuBoton">E-Quiz</div>
        <div  class="menuBoton" id="logoutHolder"></div>
    </div>
    
    <div id="footer">
    	&copy; Daniel Rodr&iacute;guez &amp; Prachi 2015
        
    </div>
    
    
     <div id="segunda" class="seccionRest">
     <div id="notificationBar">Hola</div>
	 <div id="alertBar">Hola</div>
    	<div class="headerInterior" style="text-align:center">
				<img src="equiz.jpg" height="200px" width="200px"/>
		  </div>
        <div id="contenidoInterior" class="contenidoInterior" style="text-align:center">
				<br>
                 <input class="input-rounded-button" type="button" value="Member Area" id="submit" onClick="javascript:window.location.href='memberLogin.php'" style="width:200px"/>
                 <br>
                 <br>
                 <input class="input-rounded-button" type="button" value="Quiz Maker Area" id="submit" onClick="javascript:window.location.href='makerLogin.php'" style="width:200px"/>
                 <br>
                 <br>
                 <input class="input-rounded-button" type="button" value="Public Links" id="submit" onClick="javascript:window.location.href='newPublicUserTest.php'" style="width:200px"/>
       </div>
    </div>
    
    
</body>
</html>