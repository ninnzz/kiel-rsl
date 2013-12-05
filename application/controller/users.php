<?php
class Users extends Kiel_Controller{


	/*
	* Always pass the args to the parent construct
	*
	*/
	public function __construct($args)
	{
		parent::__construct($args);
		$this->load_model('user_model');
	}

	/**
	* @param access_token
	* @param username
	* @param password
	* @param password_c
	* @param first_name
	* @param last_name
	* @param email
	* @return user_object || error message
	*/

	public function index_post()
	{
		$required = array('access_token','username','password','password_c','first_name','last_name','email');
		$this->required_fields($required,$this->post_args);
		$this->has_scopes(array('web.view','users.add'),$this->post_args['access_token']);

		$data = $this->post_args;

		if($data['password'] !== $data['password_c']){
			throw new Exception("Password is not the same!", 1);
		}
		if(!$this->user_model->username_exists($data['username'])){
			throw new Exception("Sadly, that username is taken. Try another one.", 1);
		}
		if(!$this->user_model->email_exists($data['email'])){
			throw new Exception("Sadly, that email is taken. Try another one.", 1);
		}

		$res = $this->user_model->add_user($data);
		if(!$res){
			throw new Exception("Something went wrong while adding data. Please try again.", 1);
		}
		$this->response(array('status'=>'Success','data'=>$res),200);

	}

	public function login_post()
	{
		$required = array('username','password');
		$this->required_fields($required,$this->post_args);
		
		$user = $this->user_model->get_by_username($this->post_args['username']);
		if(!$user){
			throw new Exception("Woah..! We can't find that username in our database.", 1);
		}
		if($user['password'] != md5($this->post_args['password'])){
			throw new Exception("Woah..! Username and password does not match!", 1);
		}
		if($user['active'] == 0){
			throw new Exception("Woah..! User is not an active user. Verify email or contact the site administrator", 1);
		}
		unset($user['password']);
		$this->response(array('status'=>'Success','data'=>$user),200);
	}
}

?>