<?php
class Oauth extends Kiel_Controller{


	/*
	* Always pass the args to the parent construct
	*
	*/
	public function __construct($args)
	{
		parent::__construct($args);
		$this->load_model('auth_model');
	}

	/**
	*	@param 		client_id
	*	@param 		user_id
	*	@return 	error || request_token
	*/
	public function request_token_post()
	{
		$required = array('client_id','user_id');
		$this->required_fields($required,$this->post_args);
		
		$client_id = $this->post_args['client_id'];
		$user_id = $this->post_args['user_id'];

		$c_e = $this->check_client($client_id);	
		if(!$c_e){
			$this->response(array('status'=>'Error','data'=>'Invalid client_id'),500);
		} else {
			$res = $this->auth_model->store_request_token(array('client_id'=>$client_id,'user_id'=>$user_id));
			if($res){
				$this->response(array('status'=>'Success','data'=>array('request_token'=>$res)),200);
			}
		}
	}

	/**
	*	@param 		client_id
	*	@param 		user_id
	*	@param 		request_token
	*	@return 	error || access_token
	*/
	public function access_token_post()
	{
		$required = array('client_id','request_token','scopes','user_id');
		$this->required_fields($required,$this->post_args);

		$client_id = $this->post_args['client_id'];
		$user_id = $this->post_args['user_id'];
		$request_token = $this->post_args['request_token'];
		$scopes = $this->post_args['scopes'];

		$r_c = $this->auth_model->check_request_token($request_token);
		if(!$r_c){
			$this->response(array('status'=>'Error','data'=>'Invalid/expired request_token'),500);
		} else {
			$this->auth_model->delete_access_token($user_id);

			$c_e = $this->check_client($client_id);	
			if(!$c_e){
				$this->response(array('status'=>'Error','data'=>'Invalid client_id'),500);
			} else {
				$scopes = explode(',', urldecode($scopes));
				$new_access_token = $this->auth_model->create_access_token($client_id,$user_id);
				if($new_access_token){
					$sc = $this->auth_model->add_scopes($new_access_token,$scopes);
					// print_r($sc);
					// die();
					if($sc){
						$this->auth_model->delete_request_token($request_token);
						$this->response(array('status'=>'Success','data'=>array('access_token'=>$new_access_token)),200);
					}
				} else {
					throw new Exception("Error Processing Access Token Request", 1);	
				}
			}
		}
	}

	/**
	*	@param 		client_id
	*	@param 		user_id
	*	@param 		timestamp
	*	@return 	false || request_token
	*/
	public function client_post()
	{

	}

	/**
	*	@param 		client_id
	*	@return 	true || false
	*/
	private function check_client($client_id=NULL)
	{
		if(!$client_id){
			return FALSE;
		} 
		return $this->auth_model->check_client($client_id);
	}



}

?>