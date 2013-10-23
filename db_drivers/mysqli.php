<?php
	class Connector implements data_handler{
		private $host; 
		private $username; 
		private $password; 
		private $db_name;
		private $query; 
		
		function __construct($h,$uname,$pass,$db_name) {
       		$this->host = $h;
       		$this->username = $uname;
       		$this->password = $pass;
       		$this->db_name = $db_name;
   		}

		private function load($query_message){
			$message = '';
			$row_count = 0;
			$res = array();

			$link = mysqli_connect($this->host,$this->username ,$this->password,$this->db_name) or die('Database Connection Error');
			// $link = mysqli_connect($this->$host,$this->$username ,$this->$password,$this->$db_name) or (throw new Exception("Database Connection Error", 1));
			if($link->connect_errno > 0){
				$err = $link->connect_error;
				$link->close() or die('no links to close');
				header("HTTP/1.0 500 Internal Server Error");
    			throw new Exception("Database Connection Error [" . $err . "]", 1);
			}
			$link->autocommit(FALSE);
			if(!$result = $link->query($query_message)){
				$err = $link->error;
				$link->close();
 				header("HTTP/1.0 500 Internal Server Error");
    			throw new Exception("Database Connection Error [" . $err . "]", 1);
			}

			while($row = $result->fetch_assoc()){
  		 		array_push($res, $row);
			}
			$res['result_count'] = $result->num_rows;

			$result->free();
			$link->commit();
			$link->close() or die('no links to close');
			return($res);
		}

		private function post_query($query_message){
			$message = '';
			$row_count = 0;
			$res = array();

			// $link = mysqli_connect(DBConfig::DB_HOST, DBConfig::DB_USERNAME, DBConfig::DB_PASSWORD, DBConfig::DB_NAME) or die('Database Connection Error');
			$link = mysqli_connect($this->host,$this->username ,$this->password,$this->db_name) or die('Database Connection Error');
			if($link->connect_errno > 0){
    			$err = $link->connect_error;
				$link->close() or die('no links to close');
 				header("HTTP/1.0 500 Internal Server Error");
    			throw new Exception("Database Connection Error [" . $err . "]", 1);
			}
			$link->autocommit(FALSE);
			if(!$result = $link->query($query_message)){
				$err = $link->error;
				$errNo = $link->errno;
				$affected = $link->affected_rows;
				$link->close();
				#echo $err;
 				return array('errcode'=>$errNo ,'error'=>$err,'affected_rows'=>$affected);
			}
			$res['affected_rows'] = $link->affected_rows;

			$link->commit();
			$link->close() or die('no links to close');
			return($res);
		}

		public function get($table,$selectables){
			
		}


// Start of what I am doing




    /**
     * Fetches data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be fetched
     * @param offset(int) - offset
     * @param limit(int) - limit per query
     * @param sort - sort field
     * @param order - sort order
     * @return false| object
     */
    public function query($query){
        if($query != null && $query != ""){
            $this->query = $query;
        }
    }

    public function get($table,$data,$offset,$limit,$sort,$order){

    }

    /**
     * Fetches data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be fetched
     * @param where(array) - fields to be compared
     * @param offset(int) - offset
     * @param limit(int) - limit per query
     * @param sort - sort field
     * @param order - sort order
     * @return false| object
     */
    public function get_where($table,$data,$where,$offset,$limit,$sort,$order){

    }

    /**
     * Fetches data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be inserted
     * @return false| object
     */
    public function insert($table,$data){

    }

    /**
     * Deletes data from the data source
     * @param table(string) - table/object name
     * @param where(array) - fields to be compared
     * @return false| object
     */
    public function delete($table,$where){

    }

    /**
     * updates data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be updated
     * @return false| object
     */
    public function update($table,$data){
        echo "hi";
    }

    /**
     * Updates data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be updated
     * @param where(array) - fields to be compared
     * @return false| object
     */
    public function update_where($table,$data,$where){

    }


    /**
     * To be followed
     */
    public function update_batch($table, $data=array(),$where=array()){

    }
    public function insert_batch($table, $data){

    }














	}
?>