<?php
	defined('AppAUTH') or die;

	/*
 	 * Will allow the use of oauth enabled requests
 	 *
 	 * Default value: TRUE
  	 */
	$config['oauth'] = TRUE;
	$config['oauth_applications_table'] = "applications";
	
	/*
 	 * Compresses the response using gzip endcoding
 	 *
 	 * Default value: TRUE
  	 */
	$config['compress_output'] = TRUE;


	/*
 	 * Logging options
 	 * Logging is set to true by default
 	 * 
  	 */
	$config['enable_logging'] = TRUE;
	$config['logs_table'] = "logs";

	/*
	 * Default route/class that will be called
	 *
	 *
	 */
	$config['default_route'] = "";

	/*
	 * Sets the allowed method types for the rest server
	 * Accepts arrays of allowed method types
	 *
	 */
	$config['allowed_method_types'] = array('POST','GET','PUT','DELETE');

	$config['index_path_redirect'] = "";



	$config['load_db'] = TRUE;
?>
