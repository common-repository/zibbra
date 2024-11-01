<?php
/**
 * Main file of the wordpress plugin for Zibbra
 * 
 * Plugin Name: Zibbra
 * Plugin URI: http://wordpress.org/plugins/zibbra/
 * Description: Zibbra integration plugin for Wordpress
 * Version: 1.7.6
 * Author: Zibbra
 * Author URI: https://www.zibbra.com
 * License: GPL2
 * Text Domain: zibbra
 * @package Wordpress\Plugin
 */

/**
 * BASE Directory for the Zibbra plugin
 * 
 * @var string
 */
define("ZIBBRA_BASE_DIR", realpath(__DIR__));

// Check that the library is in the include_path directive

if((@include_once "zlibrary/library.php") != TRUE || (@include_once "zlibrary/adapter/wordpress.php") != TRUE) {

	echo "Unable to load the API Client Library file 'zlibrary/library.php'. Make sure the directory is added to you include_path directive: ".get_include_path();
    exit;

} // end if

// Include plugin files

require_once(ZIBBRA_BASE_DIR."/core/controller.php");
require_once(ZIBBRA_BASE_DIR."/core/library.php");
require_once(ZIBBRA_BASE_DIR."/core/query.php");
require_once(ZIBBRA_BASE_DIR."/core/admin.php");
require_once(ZIBBRA_BASE_DIR."/core/cache.php");
require_once(ZIBBRA_BASE_DIR."/core/notify.php");
require_once(ZIBBRA_BASE_DIR."/core/ga.php");
require_once(ZIBBRA_BASE_DIR."/core/fb.php");
require_once(ZIBBRA_BASE_DIR."/core/recaptcha.php");
require_once(ZIBBRA_BASE_DIR."/modules/interface.php");
require_once(ZIBBRA_BASE_DIR."/modules/abstract.php");
require_once(ZIBBRA_BASE_DIR."/modules/catalog.php");
require_once(ZIBBRA_BASE_DIR."/modules/product.php");
require_once(ZIBBRA_BASE_DIR."/modules/cart.php");
require_once(ZIBBRA_BASE_DIR."/modules/checkout.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment/interface.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment/abstract.php");
//require_once(ZIBBRA_BASE_DIR."/modules/payment/icepay.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment/mollie.php");
//require_once(ZIBBRA_BASE_DIR."/modules/payment/ogone.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment/paypal.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment/payzen.php");
require_once(ZIBBRA_BASE_DIR."/modules/payment/transcription.php");
require_once(ZIBBRA_BASE_DIR."/modules/account.php");
require_once(ZIBBRA_BASE_DIR."/modules/brand.php");
require_once(ZIBBRA_BASE_DIR."/modules/register.php");
require_once(ZIBBRA_BASE_DIR."/modules/reset.php");
require_once(ZIBBRA_BASE_DIR."/modules/search.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping/interface.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping/abstract.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping/bpost.php");
//require_once(ZIBBRA_BASE_DIR."/modules/shipping/dpd.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping/generic.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping/kiala.php");
require_once(ZIBBRA_BASE_DIR."/modules/shipping/sprintpack.php");
require_once(ZIBBRA_BASE_DIR."/modules/sitemap.php");
require_once(ZIBBRA_BASE_DIR."/modules/track.php");
require_once(ZIBBRA_BASE_DIR."/modules/login.php");
require_once(ZIBBRA_BASE_DIR."/widgets/abstract.php");
require_once(ZIBBRA_BASE_DIR."/widgets/menu.php");
require_once(ZIBBRA_BASE_DIR."/widgets/minicart.php");
require_once(ZIBBRA_BASE_DIR."/widgets/bestsellers.php");
require_once(ZIBBRA_BASE_DIR."/widgets/category.php");
require_once(ZIBBRA_BASE_DIR."/widgets/filters.php");
require_once(ZIBBRA_BASE_DIR."/widgets/newsletter.php");
require_once(ZIBBRA_BASE_DIR."/widgets/login.php");
require_once(ZIBBRA_BASE_DIR."/widgets/brands.php");
require_once(ZIBBRA_BASE_DIR."/widgets/notify.php");
require_once(ZIBBRA_BASE_DIR."/widgets/poweredby.php");

// Create a base class for the plugin

if(!class_exists("Zibbra_Plugin")) {
	
	/**
	 * Main class for the plugin
	 * 
	 * @author Alwin Roosen <alwin.roosen@zibbra.com>
	 * @package Wordpress\Plugin
	 */
    class Zibbra_Plugin {
	
		const POST_TYPE = "zibbra";
		const LC_DOMAIN = "zibbra";
		const FORM_ACTION = "zibbra";
		const ROLE = "customer";
		const VERSION = "1.7.6";
		
		private $controller = null;
    	
		public function __construct() {
        	
			$this->controller = Zibbra_Plugin_Controller::getInstance();
            
        } // end function
    	
		public function dispatch() {
        	
			$this->controller->dispatch();
            
        } // end function
        
        public function activate() {
        	
        	$this->controller->activate();
        	
        } // end function
        
        public function deactivate() {
        	
        	$this->controller->deactivate();
        	
        } // end function
        
    } // end class
    
} // end if

// Load template tags

$tags = @scandir(ZIBBRA_BASE_DIR."/tags");

foreach($tags as $file) {
	
	if($file!="." && $file!="..") {
		
		include_once(ZIBBRA_BASE_DIR."/tags/".$file);
		
	} // end if

} // end foreach

// Dispatch the plugin

if(class_exists("Zibbra_Plugin")) {
	
	// Instantiate the plugin class
	
	$zibbra = new Zibbra_Plugin();
		
	// Installation and uninstallation hooks
	
	register_activation_hook(WP_PLUGIN_DIR."/zibbra/zibbra.php", array($zibbra, "activate"));
	register_deactivation_hook(WP_PLUGIN_DIR."/zibbra/zibbra.php", array($zibbra, "deactivate"));
	
	// Dispatch the plugin
	
	$zibbra->dispatch();
	
} // end if

?>
