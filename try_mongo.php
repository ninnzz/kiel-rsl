<?php

class Mongo_connector
{

	private $db;


	function __construct($d,$u,$p,$h)
	{
    	try 	
		{	
			$mongo = new MongoClient();
			$this->db = $mongo->selectDB($d);
		}
		catch ( MongoConnectionException $e ) 
		{
    		echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
    		exit();
		}
	}

	public function get($collection,$selected=null,$offset=0,$limit=null)
	{
		return $this->db->$collection->find();
	}

}

$d = new Mongo_connector('as',2,3,4);

?>