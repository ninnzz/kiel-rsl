<?php
	defined('AppAUTH') or die;
	
	class Kiel_Controller
	{
		protected $method;
		protected $firing_method;
		protected $api_key;
		protected $db;

		protected $post_args = array();
		protected $delete_args = array();
		protected $get_args = array();
		protected $put_args = array();
		
		protected $selected = array();




		public function checkAuth($access_token)
		{

		}

		public function setDB($db_connector)
		{
			$this->db = $db_connector;
		}

		public function getRequestData($method)
		{
			$this->method = $method;
			
			switch ($method) {
				case 'GET':
					$this->xfClean($_GET);
				break;

				case 'POST':
					$this->xfClean($_POST);
				break;
				case 'PUT':
					$this->xfClean($_PUT);
				break;
				case 'DELETE':
					$this->xfClean($_DELETE);
				break;
				default:
					header("HTTP/1.0 500 Internal Server Error");
					throw new Exception("Invalid method", 1);
					break;
			}

		}

		private function xfClean($args)
		{
			$tmp = array();
			foreach ($args as $key => $value){
			    $tmp[$key] =  strip_tags(filter_var($value,FILTER_SANITIZE_ENCODED));
				array_push($this->selected,$key);
			}

			switch (strtolower($this->method)) {
				case 'get':
					$this->get_args = $tmp;
					break;
				case 'post':
					$this->post_args = $tmp;
					break;
				case 'delete':
					$this->delete_args = $tmp;
					break;
				case 'put':
					$this->put_args = $tmp;
					break;
			}
		}
	}
?>
