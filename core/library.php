<?php

class Zibbra_Plugin_Library {

	private $adapter = null;
	private $library = null;
	private $configured = false;
	
	public function __construct() {

		// Check options
		
		if(get_option("zibbra_api_client_id")!==false && get_option("zibbra_api_client_secret")!==false) {
		
			// Create the library connection
			
			$this->adapter = new ZLibrary_Adapter_Wordpress();
			$this->library = ZLibrary::getInstance($this->adapter);
			
			// Init library
			
			$this->library->setApiClientId(get_option("zibbra_api_client_id"));
			$this->library->setApiClientSecret(get_option("zibbra_api_client_secret"));

			$env = constant("ZLibrary::API_URI_".get_option("zibbra_api_env","PRODUCTION"));
			$this->library->setEnv($env);

			if(get_option("zibbra_debug","N")=="Y") {

				$this->library->enableDebug();

			} // end if
			
			// Adapter configuration

			$logdir = get_option("zibbra_log_dir", WP_CONTENT_DIR);
			$logdir = is_dir($logdir) && is_writable($logdir) ? $logdir : WP_CONTENT_DIR;
			
			$this->adapter->setLogDir($logdir);
			
			if(isset($_SESSION['lang'])) {
			
				$this->adapter->setLanguageCode(str_replace("_","-",strtolower($_SESSION['lang'])));
			
			} // end if
			
			// Mark as configured
			
			$this->configured = true;
		
		} // end if

	} // end function
	
	public function isConfigured() {
		
		return $this->configured;
		
	} // end function
	
	public function getAdapter() {
		
		return $this->adapter;
		
	} // end function
	
} // end class

?>