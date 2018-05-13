<?php

//require_once("../DAO/memberDAO.php");
//require_once("../DAO/quizMakerDAO.php");
require_once(dirname(__FILE__)."\\..\\DAO\\memberDAO.php");
require_once(dirname(__FILE__)."\\..\\DAO\\quizMakerDAO.php");


require_once("HttpResource.php");

class EquizResource extends HttpResource{
	
	 public function is_member() {
		 
		 session_start();
		 return isset($_SESSION['id_member']);
	 }
	
		
	 public function is_quiz_maker() {
		 
		
		 session_start();
		 return isset($_SESSION['id_quiz_maker']);
	 }
	
}