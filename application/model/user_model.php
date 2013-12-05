<?php
class User_model extends Kiel_Model{

	public function __construct()
	{
		parent::__construct();
	}

	public function username_exists($username)
	{
		$res = $this->data_handler->get_where(OAUTH_USERS,null,array('username'=>$username));
		return $res['result_count'] === 0?TRUE:FALSE;
	}	
	public function email_exists($email)
	{
		$res = $this->data_handler->get_where(OAUTH_USERS,null,array('email'=>$email));
		return $res['result_count'] === 0?TRUE:FALSE;
	}
	public function add_user($data)
	{
		$data['id'] = sha1($data['username'].$this->time.$data['email'].'users'.openssl_random_pseudo_bytes(20));
		$data['active'] = 0;
		$data['password'] = md5($data['password']);
		$data['date_created'] = $this->time;
		$data['date_updated'] = $this->time;
		unset($data['access_token']);
		unset($data['password_c']);
		$res = $this->data_handler->insert(OAUTH_USERS,$data);
		if($res['affected_rows'] == 1){
			return $data;
		} else {
			return false;
		}
	}
	public function get_by_username($uname)
	{
		$res = $this->data_handler->get_where(OAUTH_USERS,null,array('username'=>$uname));
		return $res['result_count'] === 0?FALSE:$res['result'][0];
	}

}


?>