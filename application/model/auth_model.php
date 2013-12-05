<?php
class Auth_model extends Kiel_Model{

	public function store_request_token($data)
	{	
		$data['request_token'] = md5($this->time.'request_token');
		$data['expires'] = $this->time + REQUEST_TOKEN_EXPIRES;
		$res = $this->data_handler->insert(OAUTH_REQUEST_TOKEN_TABLE,$data);
		if($res['affected_rows'] == 1){
			return $data['request_token'];
		} else {
			return false;
		}

	}

	public function check_client($client_id)
	{	
		$res = $this->data_handler->get_where(OAUTH_CLIENTS_TABLE,null,array('client_id'=>$client_id));
		return $res['result_count'] == 0?FALSE:TRUE;
	}

	public function check_request_token($request_token)
	{
		$res = $this->data_handler->get_where(OAUTH_REQUEST_TOKEN_TABLE,null,array('request_token'=>$request_token));
		if($res['result_count'] == 1){
			if($this->time > $res['result'][0]['expires']*1){
				return false;
			} else{
				return true;
			}
		} else {
			return false;
		}

	}

	public function delete_access_token($user_id=NULL,$access_token=NULL)
	{
		if($user_id){
			$at = $this->data_handler->get_where(OAUTH_ACCESS_TOKEN_TABLE,null,array('user_id'=>$user_id));
			$at = $at['result_count'] == 0?'0':$at['result'][0]['access_token'];
			
			$this->data_handler->delete(OAUTH_SCOPES,array('access_token'=>$at));
			$this->data_handler->delete(OAUTH_ACCESS_TOKEN_TABLE,array('access_token'=>$at));
		}
	}

	public function delete_request_token($request_token)
	{
		$this->data_handler->delete(OAUTH_REQUEST_TOKEN_TABLE,array('request_token'=>$request_token));
	}

	public function create_access_token($client_id,$user_id)
	{
		$data['client_id'] = $client_id;
		$data['user_id'] = $user_id;
		$data['expires'] = ACCESS_TOKEN_EXPIRES === 0?0:($this->time+ACCESS_TOKEN_EXPIRES);
		$data['access_token'] = sha1($client_id.$this->time.$user_id.'access_token'.openssl_random_pseudo_bytes(20));
		$res = $this->data_handler->insert(OAUTH_ACCESS_TOKEN_TABLE,$data);
		if($res['affected_rows'] == 1){
			return $data['access_token'];
		} else {
			return false;
		}
	}

	public function add_scopes($access_token = NULL, $scopes = NULL)
	{
		if(!$access_token || !$scopes || !is_array($scopes)){
			return false;
		}

		foreach ($scopes as  $scope) {
			$data['access_token'] = $access_token;
			$data['scope'] = trim($scope);
			$this->data_handler->insert(OAUTH_SCOPES,$data);
		}
		return true;
	}

	public function get_scopes($access_token)
	{
		$arr = array();
		$res = $this->data_handler->get_where(OAUTH_SCOPES,array('scope'),array('access_token'=>$access_token));
		foreach ($res['result'] as $scope) {
			array_push($arr, $scope['scope']);
		}
		return $arr;
	}

}

?>