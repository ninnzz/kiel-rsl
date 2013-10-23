<?php
class User extends Kiel_Controller{

	public function __construct()
	{
		$this->load_model('user_model');
	}

	/**
	* == Login for users ==
	* 
	* @param 		username
	* @param 		password
	* @author 		Ninz Eclarin |  nreclarin@gmail.com
	* @return 		user_id
	* @version 		Version 1.0
	* 
	*/
	public function login_post()
	{

		$required_field = array('username','password');
		$this->required_fields($required_field,$this->post_args);

		$this->response(array('ok'=>'hahaha'),200);

	}

	public function hello_get()
	{

		echo "hey";
        $this->response(array('ok'=>'hahaha'),200);

	}

}

?>

