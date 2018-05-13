<?php

class Node{

	private $text;
	private $line;
	private $column;
	private $childs = array();
	
	public function __construct($text, $line, $column){
		
		$this->text = $text;
		$this->line = $line;
		$this->column = $column;
		
	}
	public function getText(){
		return $this->text;	
	}
	public function getLine(){
		return $this->line;	
	}
	public function getColumn(){
		return $this->column;	
	}
	public function addChild($nodeChild){
		array_push($this->childs,$nodeChild);	
	}
	public function getChilds(){
		return $this->childs;	
	}
	
	public static function printNode($node, $level){
		echo  "<".get_class($node)."> ".$node->text;
		
		foreach($node->childs as $child){
			echo "\n";
			echo str_repeat("\t",$level+1);
			Node::printNode($child,$level+1);
			
			
		}
	    echo str_repeat("\t",$level);
		echo"</".get_class($node).">";
		echo "\n";
	}
	
	
}

class QuestionNode extends Node{
	
}

class OptionNode extends Node{
	
	public function iAmCorrect(){
		return false;	
	}
	
}

class AnswerNode extends Node{
	
	public function iAmCorrect(){
		return true;	
	}
}

class TestNode extends Node{

}