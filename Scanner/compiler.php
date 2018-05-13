<?php

require_once("parser.php");
require_once("node.php");

class Compiler{
	
	private $arrayS;
	
	
	public function __construct($multipleArray){
		$this->arrayS = $multipleArray;
	}
	
	public function sintaxCheck(){

		for($i=0; $i<sizeof($this->arrayS); $i++){
			if( sizeof($this->arrayS[$i]) != 2){
				throw new Exception("More than 2 columns on the line: ".++$i);
				return $i;	
			}
			if( !$this->verifyPrefix($this->arrayS[$i][0]) ){
				throw new Exception("Unrecognized character ". $this->arrayS[$i][0]." at line ".++$i);
				return $i;	
			}
			if( empty(trim($this->arrayS[$i][1])) && $this->arrayS[$i][1] !=0){
				throw new Exception("Empty value at line ".++$i);
			}
			
		}
		return -1; //Success
	}
	public function generateTree(){
		$parent = new TestNode("","","");
		for($i=0; $i<sizeof($this->arrayS); $i++){	
			switch( $this->arrayS[$i][0] ){
				case 'Q':
					$questionTemp = new QuestionNode($this->arrayS[$i][1],$i+1,2);
					while( ( $i+1 < sizeof($this->arrayS)) && ($this->arrayS[$i+1][0] == 'O' || $this->arrayS[$i+1][0] == 'A') ){
						$i++;
						switch( $this->arrayS[$i][0] ){
							case 'O':
								$questionTemp->addChild(new OptionNode($this->arrayS[$i][1],$i+1,2));
								break;
							case 'A':
								$questionTemp->addChild(new AnswerNode($this->arrayS[$i][1],$i+1,2));
								break;
							default:
								throw new Exception("Expecting 'O' or 'A' but encountered '".$this->arrayS[$i][0]."' at line ".++$i);
						}
					};
					
					$parent->addChild($questionTemp);	
					break;
				default:
					throw new Exception("Expecting 'Q' but encountered '".$this->arrayS[$i][0]."' at line ".++$i);
			}
		}		
		return $parent;
	}
	
	private function semanticCheck(TestNode $node){
		$childs = $node->getChilds();
		if(sizeof($childs) <=0) throw new Exception("No questions found on the file");
		foreach($childs as $childNode){
			$this->VisitQuestion($childNode);	
		}
	}
	
	private function VisitQuestion(QuestionNode $node){
		$numberAnswer = 0;
		$numberOptions = 0;
		foreach	($node->getChilds() as $child){
			switch(get_class($child)){
				case "OptionNode":
					$numberOptions++;
					break;
				case "AnswerNode":
					$numberOptions++;
					$numberAnswer++;
					break;
				default:
					throw new Exception("Expecting Option or Answer but found ".get_class($child));	
					
			}
		}
		if($numberAnswer > 1) throw new Exception("The question : '".$node->getText()."' has more than 1 answer at line ".$node->getLine());
		if($numberAnswer < 1) throw new Exception("The question : '".$node->getText()."' has no answer declared at line ".$node->getLine());
		
		if($numberOptions<2) throw new Exception("The question : '".$node->getText()."' has less than 2 options");
	}
	
	private function verifyPrefix($str){
		if($str == "Q" || $str == "A" ||  $str == "O"){
			return true;	
		}
		return false;
	}

	public function run(){
		$st = $this->sintaxCheck();
		$node = $this->generateTree();
				Node::printNode($node,0);		

		$this->semanticCheck($node);
		return $node;
	}
	
}
