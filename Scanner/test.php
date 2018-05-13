<?php
	//header("Content-type: text/xml");
	require_once("parser.php");
	require_once("compiler.php");
	
	
	$file = file_get_contents("test.csv");

	$p = new Parser($file);
	
	$arr = $p->getArray();
	
	//print_r($arr);
	
	$scanner = new Compiler($arr);
	
	try{
		$scanner->run();
		echo "OK";
	}catch(Exception $e){
		echo $e->getMessage();	
	}
	
	
	
