<?php
	class Connector implements data_handler{
		private $host; 
		private $username; 
		private $password; 
		private $db_name;
		private $query; 
		private $conn;
        private $result;

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

// Start of what I am doing


    public function execute(){
        $this->conn = mysqli_connect($this->host,$this->username ,$this->password,$this->db_name) or die('Database Connection Error');

        $this->result = mysqli_query($this->conn,$this->query);

        mysqli_close($this->conn);

        if($this->result === FALSE){
            return false;
        }

        return mysqli_fetch_all($this->result, MYSQLI_ASSOC);
    }

    public function query($query){
        $message = '';
        $row_count = 0;
        $this->query = $query;

        return $this->execute();
    }

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

    private function include_data($data){
        for($i = 0, $j = count($data)-1; $i<$j; $i++)
            $this->query .= $data[$i] . ',';
        $this->query .= $data[$i] . ' ';
    }

    private function include_eq($where, $keyword){

        if($where != null){
            $this->query .= "$keyword ";
            while($element = current($where)) {

                is_string($element)?$element="'" . $element . "'":1;

                $this->query .= key($where) . '=' . $element . ' ,';
                next($where);
            }
            $this->query = substr($this->query, 0, $this->query.Length - 1);

            echo $this->query;
        }
    }

    private function include_hr($arg1, $arg2, $keyword1, $keyword2){
        if(!is_null($arg1)){
            $this->query .= $keyword1 . ' ' . $arg1 . ' ';
            if(!is_null($arg2))
                $this->query .= $keyword2 . ' ' . $arg2 . ' ';
        }
    }
    /** note, ung ginawa ko is required lahat na may laman. Don't know why, haha */

    public function get($table,$data,$offset,$limit,$sort,$order){
        $this->query = "SELECT ";
        $this->include_data($data);
        $this->query .= "FROM " . $table . ' ';
        $this->include_hr($sort,$order, "ORDER BY", "");
        $this->include_hr($limit, $offset, "LIMIT", "OFFSET");

        echo $this->query;

        return $this->execute();

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
        $this->query = "SELECT ";
        $this->include_csv($data);
        $this->query .= "FROM " . $table . ' ';
        $this->include_eq($where, "WHERE");
        $this->include_hr($sort,$order, "ORDER BY", "");
        $this->include_hr($limit, $offset, "LIMIT", "OFFSET");

//        return $this->execute();

    }

    /**
     * Fetches data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be inserted
     * @return false| object
     */
    public function insert($table,$data){
        $this->query = "INSERT INTO " . $table . ' ';
        $this->query .= "VALUES( ";
        $this->include_csv($data);
        $this->query .= ")";

        return $this->execute();
    }

    /**
     * Deletes data from the data source
     * @param table(string) - table/object name
     * @param where(array) - fields to be compared
     * @return false| object
     */
    public function delete($table,$where){
        $this->query = "DELETE FROM " . $table . ' ';
        $this->include_eq($where, "WHERE");

        return $this->execute();
    }

    /**
     * updates data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be updated
     * @return false| object
     */
    public function update($table,$data){
        $this->query = "UPDATE " . $table . ' ';
        $this->include_eq($data,"SET");

        return $this->execute();
    }

    /**
     * Updates data from the data source
     * @param table(string) - table/object name
     * @param data(array) - data to be updated
     * @param where(array) - fields to be compared
     * @return false| object
     */
    public function update_where($table,$data,$where){
        $this->query = "UPDATE " . $table . ' ';
        $this->include_eq($data, "SET");
        $this->include_eq($where, "WHERE");

        return $this->execute();
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