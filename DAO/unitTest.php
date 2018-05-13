<?php

	require_once("../model/level.php");
	require_once("levelDAO.php");
	
	$level = new Level();
	
	$level->setName("Test Level");
	
	$levelDAO = new LevelMysqlDAO();
	
	try{
		//$levelDAO->write($level);
		
		$sameLevel = $levelDAO->search("2");
		
		print_r($sameLevel);
		
		$sameLevel->setName("Level updated");
		
		$levelDAO->update($sameLevel);
	}catch(Exception $e){
		die( $e->getMessage() );
	}

?>