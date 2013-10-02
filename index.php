<?php
	
	/* == DEFINES APPLICATION ENVIRONMENT ==
	 *
	 * Can create custom value but default values are set
	 * 
	 * development
	 * production
	 *
  	 */
	$start = microtime(true); 
	define('ENVIRONMENT', 'development');

	/* == ERROR REPORTING ==
	 *
	 * Shows erros depending on the environment
	 * All error is shown in the development environment
	 *
	 */
	if (defined('ENVIRONMENT'))
	{
		switch (ENVIRONMENT)
		{
			case 'development':
				error_reporting(E_ALL);
			break;		
			case 'production':
				error_reporting(0);
			break;
			default:
				header('Content-Type: application/json');
				header("HTTP/1.0 500 Internal Server Error");
				print_r(json_encode(array("message"=>"Invalid environemnt setup")));
				exit();
		}
	}


	/* == DIRECTORY HANDLERS ==
	 *
	 * system path resolution
	 *
	 */
	$core_path = "core";
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}


	if (realpath($core_path) !== FALSE)
	{
		$core_path = realpath($core_path).'/';
	}

	$core_path = rtrim($core_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($core_path))
	{
		header('Content-Type: application/json');
		header("HTTP/1.0 500 Internal Server Error");
		print_r(json_encode(array("message"=>"Invalid core path")));
	}


	header('Content-Type: application/json');
	try{
		require_once("core/KielRSLCore.php");

		$end = (microtime(true) - $start);
	} catch(Exception $e){

		$end = round((microtime(true) - $start),5);
		print_r(json_encode(array("error"=>$e->getMessage(),"ellapsed time"=>$end,"object"=>$object_name,"method"=>$method_name)));
		exit();
	}

?>