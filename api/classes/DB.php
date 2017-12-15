<?php
class DB{
	private $db;

	public function __construct($host,$dbname,$user,$pass){
        $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;
     }

    public function query($query,$params = array()){
    	$statement = $this->db->prepare($query);
    	$statement->execute($params);

         if(explode(' ',$query)[0] == 'SELECT'){
           $data = $statement->fetchAll(PDO::FETCH_ASSOC);
           return $data;
         }
    }    
}