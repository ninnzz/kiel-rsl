<?php
class Auth extends Kiel_Controller{

	public function index_post(){
	
		$res = $this->db->load("select * from school");
		print_r($res);
	}
	public function index_get(){

		echo $this->get_args['name'];

	}

}

?>