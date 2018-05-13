<?php


class Parser{

	private $str;
	
	public function __construct($str){
		
		$this->str = $str;
		
	}
	
	public function getArray(){
		
		$singleArray = explode("\n",$this->str);

		$doubleArray = array();
		
		foreach( $singleArray as $line){
			if(!empty($line))
				array_push($doubleArray, explode(",",$line));	
			
		}
		return $doubleArray;
		
	}
	
	
}