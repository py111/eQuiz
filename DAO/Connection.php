<?php
class Connection{
	
	private $link;
	private $data;
	private $arreglo;
	private $row;
	
	public function __construct($DBname, $DBuser, $DBpw){
		error_reporting(0); 
		$this->link = mysql_connect('localhost', $DBuser, $DBpw) or trigger_error(mysql_error(), E_USER_ERROR);
		mysql_select_db($DBname, $this->link) or trigger_error(mysql_error(),E_USER_ERROR);
		$this->row = 0;
		
		$this->data = Array();
		$this->arreglo = Array();
	}
	
	public function query($queryString, $nombreConsulta = 'default'){
		$this->data[$nombreConsulta] = mysql_query($queryString, $this->link);
		return $this->data[$nombreConsulta];
	}
	
	public function nextArray( $nombreConsulta='default' ){
		$this->arreglo[ $nombreConsulta ] = mysql_fetch_array($this->data[ $nombreConsulta ]);
		return $this->arreglo[ $nombreConsulta ];
	}
	
	public function getField( $campo, $nombreConsulta = 'default' ){
		return $this->arreglo[$nombreConsulta][ $campo ];
	}
	
	public function getError(){
		return mysql_errno($this->link);	
	}
	
	public function getQueryData($nombreConsulta = 'default'){
		return $this->data[$nombreConsulta];
	}
	
	public function getLinkMySQL(){
		return $this->link;
	}
	
	public function __destruct(){
		//mysql_close( $this->link );
	}
	
	public function getLastId(){
		return mysql_insert_id();
	}
	
	public function getNumResults( $nombreConsulta = 'default'){
		return mysql_num_rows( $this->data[$nombreConsulta] );
	}
	
	public function wasUpdated(){
		$affected = mysql_affected_rows();
		if($affected > 0)
		   return true;
		else
		  return false;	
	}
}

class myConnection extends Connection	{
	public function __construct(){
		parent::__construct('equiz','root','');
	}
}
?>