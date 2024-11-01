<?php

class Zibbra_Plugin_Controller {
	
	private static $instance = null;
	
	private $library = null;
	private $admin = null;
	private $module = null;
	private $ga = null;
    private $fb = null;
	private $modules = array();
	
	private function __construct() {

		// Check for the .supended file

		if(is_file(ABSPATH . ".suspended")) {

			include ZIBBRA_BASE_DIR . "/templates/suspended.php";
			exit;

		} // end if
		
		// Start session
		
		if(!session_id()) {
			
			@session_start();
			
		} // end if
		
		// Load library
		
		$this->library = new Zibbra_Plugin_Library();
		
		// Load admin
		
		$this->admin = new Zibbra_Plugin_Admin();
		
		// Check if the library is already configured
		
		if(!$this->library->isConfigured()) {
			
			// Register an action to redirect to the configuration page
			
			add_action("activated_plugin", array($this, "activation_redirect"), 10, 1);			
			
		} // end if
		
		// Create Zibbra Query
		
		z_reset_query();
		
	} // end function
	
	private function __clone() {
		
	} // end function
	
	public function dispatch() {

		if($this->library->isConfigured()) {

			// Language stuff
		
			$this->init_locale();
		
			// Load modules and widgets
		
			foreach(get_declared_classes() as $className) {
			
				// Check if this class is a module
			
				if(is_subclass_of($className, "Zibbra_Plugin_Module_Abstract")) {
				
					$module = new $className($this);
					$this->modules[$module->getModuleName()] = $module;
				
				} // end if
			
				// Check if this class is a widget
			
				if(is_subclass_of($className, "Zibbra_Plugin_Widget_Abstract")) {
				
					$this->widgets[] = $className;
				
				} // end if
			
			} // end foreach
		
			// Load Google Analytics if enabled
		
			$trackingid = get_option("zibbra_ga_tracking_id",null);
		
			if(!empty($trackingid)) {
			
				$this->ga = new Zibbra_Plugin_Ga($trackingid);
			
				if(get_option("zibbra_ga_enable_ecommerce","Y")=="Y") {
				
					$this->ga->enableEcommerce();
				
				} // end if
			
			} // end if

		    // Load Facebook Pixel if enabled

		    $trackingid = get_option("zibbra_fb_tracking_id",null);

		    if(!empty($trackingid)) {

		        $this->fb = new Zibbra_Plugin_Fb($trackingid);

		    } // end if
		
			// Register actions

			add_action("init", array($this,"register_ajax"));
			add_action("parse_query", array($this,"process_post"));
			add_action("plugins_loaded", array($this, "load_plugin_textdomain"));
			add_action("generate_rewrite_rules", array($this, "generate_rewrite_rules"));
			add_action("widgets_init", array($this, "widgets_init"));
			add_action("wp_authenticate", array($this, "authenticate"), 10, 2);
			add_action("wp_logout", array($this, "logout"));
			add_action("add_meta_boxes", array($this, "add_meta_boxes"));
			add_action("save_post", array($this, "save_post"));		
			add_action("wp_login_failed", array($this, "login_failed"));
			add_action("wp_head", array($this, "register_ga"));
		    add_action("wp_head", array($this, "register_fb"));
			add_action("pre_get_posts", array($this, "pre_get_posts"));
		
			// Register filters	
	
			add_filter("query_vars", array($this, "query_vars"));
			add_filter("template_include", array($this, "template_include"));	
			add_filter("login_url", array($this, "login_url"));
			add_filter("register_url", array($this, "register_url"));
			add_filter("lostpassword_url", array($this, "lostpassword_url"));
			add_filter("show_admin_bar", "__return_false");
			add_filter("body_class", array($this, "body_class"));

			add_filter("login_form_bottom", function() {

		        $register_params = ($this->getActiveModule() instanceof Zibbra_Plugin_Module_Checkout) ? "?return=".urlencode(site_url("/zibbra/checkout/")) : "";
		
				$html = "<p class=\"login-links\">";
				$html .= "<a href=\"".site_url("/zibbra/reset/")."\" class=\"lostpassword\">".__("Forgot your password?", Zibbra_Plugin::LC_DOMAIN)."</a>";
				$html .= "<a href=\"".site_url("/zibbra/register/").$register_params."\" class=\"register\">".__("Register a new account", Zibbra_Plugin::LC_DOMAIN)."</a>";
				$html .= "</p>";
				$html .= "<div class=\"clearfix\"></div>";
		
				return $html;
		
			});
		
			if(!is_admin()) {
		
				add_filter("edit_profile_url", function($url, $user_id, $scheme) {
				
					return site_url("/zibbra/account/");
				
				}, 10, 3);
		
			} // end if
		
			// Register CSS and JS
		
			wp_enqueue_style("wp-plugin-zibbra", plugins_url("css/zibbra.css",ZIBBRA_BASE_DIR."/css"));
			wp_enqueue_script("wp-plugin-zibbra", plugins_url("jscripts/zibbra.js",ZIBBRA_BASE_DIR."/jscripts"),array("jquery"));
			add_action("wp_footer", function() {
				echo "<script> zibbra.setSiteUrl('" . site_url() . "') </script>";
			});
		
		} // end if
		
	} // end function
	
	public function setActiveModule(Zibbra_Plugin_Module_Abstract $module) {
		
		$this->module = $module;
		
	} // end function

	/**
	 * @return Zibbra_Plugin_Module_Abstract
	 */
	public function getActiveModule() {
		
		return $this->module;
		
	} // end function
	
	public static function getInstance() {
		
		if(self::$instance===null) {
			
			self::$instance = new Zibbra_Plugin_Controller();			
			
		} // end if
		
		return self::$instance;
		
	} // end function
	
	public function getGa() {
		
		return !empty($this->ga) ? $this->ga : false;
		
	} // end function

    public function getFb() {

        return !empty($this->fb) ? $this->fb : false;

    } // end function
	
	public function getLibrary() {
		
		return $this->library;
		
	} // end function
	
	public function activate() {
		
		$this->flush_rewrite_rules();
		
		add_role(Zibbra_Plugin::ROLE,__("Zibbra Customer", Zibbra_Plugin::LC_DOMAIN), array("read"=>true, "level_0"=>true));
		
	} // end function
	
	public function activation_redirect($plugin) {
				
		if($plugin=="zibbra/zibbra.php") {
			
			exit(wp_redirect(admin_url("options-general.php?page=zibbra_plugin")));
			
		} // end if
		
	} // end function
	
	public function deactivate() {
		
		$this->remove_rewrite_rules();
		$this->flush_rewrite_rules();
		
		remove_role("customer");
		
	} // end function
	
	public function init_locale() {
		
		$locale = null;
	
		if(is_multisite()) {
			
			if(defined("WP_INSTALLING") || (false === $locale = get_option("WPLANG"))) {
				
				$locale = get_site_option("WPLANG");
				
			} // end if
			
		}else{
			
			if(false !== $locale = get_option("WPLANG") && $locale!=="en_US") {
				
				$locale = get_site_option("WPLANG");
				
			} // end if
		
		} // end if
		
		if(!$locale) {
			
			if(class_exists("Locale")) {
			
				$locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			
			}else{
			
				$locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
				
			} // end if
			
		} // end if
			
		switch($locale) {
			
			case "fr":					
			case "fr_FR":
			case "fr_BE": {
				
				$locale = "fr_BE";
				
			};break;

			case "nl":
			case "nl_NL":
			case "nl_BE": {
				
				$locale = "nl_BE";
				
			};break;
			
			default: {
				
				$locale = "en_US";
				
			};break;
			
		} // end switch
		
		if(!isset($_SESSION['lang']) || $_SESSION['lang']!=$locale) {
			
			$_SESSION['lang'] = $locale;
			
			$adapter = $this->getLibrary()->getAdapter();
			
			if(!empty($adapter)) {
				
				$adapter->setLanguageCode(str_replace("_","-",strtolower($locale)));
			
			} // end if
		
		} // end if
		
	} // end function
	
	public function load_plugin_textdomain() {
	
		add_filter("locale", function($lang) {
			
			if(!isset($_SESSION['lang'])) {
				
				$_SESSION['lang'] = $lang;
			
			} // end if
			
			if(isset($_GET['l'])) {
				
				$_SESSION['lang'] = sanitize_text_field($_GET['l']);
			
			} // end if
			
			return $_SESSION['lang'];
		
		}); // end add_filter
		
		load_plugin_textdomain(Zibbra_Plugin::LC_DOMAIN, false, dirname(plugin_basename(__DIR__))."/languages/");
		
	} // end function

	public function pre_get_posts(WP_Query $query) {

		// Disable loading of any posts when in Zibbra pages

		if(!is_admin() && $query->is_main_query()) {

			$query->query_vars['cat'] = -1;

		} // end if

		// Custom search => set post type to zibbra so our custom module is loaded

		if(!is_admin() && $query->is_main_query() && $query->is_search()) {

			$query->set("zibbra", "search");

		} // end if

	} // end function
	
	public function generate_rewrite_rules(WP_Rewrite $wp_rewrite) {

		foreach($this->modules as $module) {

			$module->generate_rewrite_rules($wp_rewrite);
				
		} // end foreach
		
	} // end function
	
	public function process_post() {

		if(!empty($_POST) && isset($_POST[Zibbra_Plugin::FORM_ACTION])) {

			foreach($this->modules as $module) {

				$module->process_post();

			} // end foreach

		} // end if
		
	} // end function
	
	public function register_ajax() {

		foreach($this->modules as $module) {

			$module->register_ajax();
				
		} // end foreach
		
	} // end function
	
	public function widgets_init() {

		foreach($this->widgets as $className) {

			register_widget($className);
				
		} // end foreach
		
	} // end function
	
	public function authenticate($username, &$password) {
		
		global $wpdb;

		$adapter = Zibbra_Plugin_Controller::getInstance()->getLibrary()->getAdapter();

		if(empty($username) || empty($password)) {

			if(!$this->login_is_admin()) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to login", Zibbra_Plugin::LC_DOMAIN));
				$adapter->log(LOG_ERR, "Empty credentials");

				wp_redirect($_SERVER['HTTP_REFERER']);
				exit;

			} // end if

			return;
			
		} // end if
		
		$password_lost_link = " (<small><a href=\"".wp_lostpassword_url()."\" style=\"font-weight:normal;\">".__("Forgot your password?", Zibbra_Plugin::LC_DOMAIN)."</a></small>)";
		$hostname = $_SERVER['HTTP_HOST'];
		$hash = md5($hostname."|".$username);

		if(!is_int(username_exists($username))) {
				
			if(!ZCustomer::login($username, $password)) {
			
				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to login", Zibbra_Plugin::LC_DOMAIN).$password_lost_link);
				$adapter->log(LOG_ERR,"User does not exist in Wordpress, login at Zibbra API failed");
				
				$password = "-";
				
			}else{
				
				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Thank you for your registration", Zibbra_Plugin::LC_DOMAIN));
				$adapter->log(LOG_DEBUG,"User does not exist in Wordpress, login at Zibbra API success, creating new Wordpress user");

				$userdata = array(
					"user_login" => $username,
					"user_pass" => $hash,
					"user_email" => md5($username)."@".$hostname,
					"role" => Zibbra_Plugin::ROLE
				);

				$user_id = wp_insert_user($userdata);
                $password = $hash;

                if(is_wp_error($user_id)) {

                    Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to create your account", Zibbra_Plugin::LC_DOMAIN).$password_lost_link);
                    $adapter->log(LOG_ERR, "Login at Zibbra API successful, but unable to save wordpress user");

                    $password = "-";

                } // end if
				
			} // end if
			
			return;
			
		}else{
		
			$userinfo = get_user_by("login", $username);
			
			$property = $wpdb->prefix."capabilities";
			
			$caps = $userinfo->$property;
			
			if(empty($caps)) {
			
				// Role not found, add it here
				
				wp_update_user(array("ID"=>$userinfo->data->ID, "role"=>Zibbra_Plugin::ROLE));
			
				$caps = $userinfo->$property;
			
			} // end if
			
			if(!is_array($caps)) {
				
				$caps = array(
					Zibbra_Plugin::ROLE=>true
				);
				
			} // end if
	
			foreach($caps as $role=>$enabled) {
				
				if($role=="administrator") {
					
					break;
					
				} // end if
				
				if($role==Zibbra_Plugin::ROLE) {
					
					if(!ZCustomer::login($username, $password)) {
						
						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to login", Zibbra_Plugin::LC_DOMAIN).$password_lost_link);
						$adapter->log(LOG_ERR,"User exists in Wordpress, login at Zibbra API failed");
					
						$password = "-";
				
					}else{
				
						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Welcome back", Zibbra_Plugin::LC_DOMAIN).(!empty($userinfo->first_name) ? " ".$userinfo->first_name." ".$userinfo->last_name : ""));
						$adapter->log(LOG_DEBUG,"User exists in Wordpress, login at Zibbra API success, updating Wordpress password");
						
						wp_set_password($hash,$userinfo->ID);
						$password = $hash;
						
					} // end if
					
					return;
					
				} // end if
				
			} // end foreach
			
		} // end if
			
	} // end function
	
	public function login_failed() {
	
		if(!$this->login_is_admin()) {

			$referrer = $_SERVER['HTTP_REFERER'];

			if(!empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin')) {

				wp_redirect($referrer.'?login=failed');
				exit;

			} // end if
		
			wp_redirect(home_url());
			exit;
		
		} // end if
		
	} // end function

	private function login_is_admin() {

        $redirect_to = false;

        if(isset($_GET['redirect_to'])) {

            $redirect_to = esc_url_raw( $_GET['redirect_to'], ['http','https'] );

        } // end if

        if(!is_admin() && ($redirect_to === false || !preg_match("/\/wp-admin\/$/",$redirect_to))) {

            return false;

        } // end if

		return true;

	} // end function
	
	public function logout() {
		
		ZCustomer::logout();
		
		session_destroy();
			
	} // end function
	
	/**
	 * @todo Try to implement this system with template replacements, instead of wokring with dummy posts
	 * @param unknown $template
	 * @return unknown|Ambigous <string, unknown>|boolean
	 */
	public function template_include($template) {
		
		global $wp_query;
	
		$post_id = get_the_ID();
		$post_type = $wp_query->get("zibbra");
		
		if($post_type === "") {
		
			$wp_query->is_zibbra = false;
			
			return $template;
			
		} // end if
		
		$wp_query->is_zibbra = true;
		$wp_query->is_home = false;

		foreach($this->modules as $module) {
				
			// Add an action to override template inclusion

			add_action("get_template_part_".$module->getModuleName(), "get_zibbra_template_part", 10, 2);
			
			// Check if this module represents the current template
			
			$check = $module->template_include($wp_query);

			if(!empty($check) && $check!==false) {
		
				// Mark this module as the active module
			
				$this->setActiveModule($module);
				
				// Override the template name
				
				if(is_array($check)) {
					
					$template = $this->override_template($check[0], $check[1]);
					
				}else{
					
					$template = $this->override_template($check);
				
				} // end if
				
			} // end if
				
		} // end foreach
			
		return $template;
	
	} // end function
	
	/**
	 * @param unknown $template
	 * @return Ambigous <string, unknown>
	 */
	private function override_template($template, $slug=null) {
	
		// Get the template slug
		
		$template = preg_replace("/\.php$/", "", $template).(!empty($slug) ? "-".$slug : "").".php";
	
		// Check if a custom template exists in the theme folder, if not, load the plugin template file
		
		if($theme_file = locate_template(array("zibbra_templates/".$template))) {
			
			$template = $theme_file;
			
		}else{
			
			$template = ZIBBRA_BASE_DIR."/templates/".$template;
			
		} // end if
		
		return $template;
		
	} // end function
	
	public function query_vars($public_query_vars) {

		foreach($this->modules as $module) {

			$public_query_vars = $module->query_vars($public_query_vars);
				
		} // end foreach
		
		return $public_query_vars;
		
	} // end function
	
	public function add_meta_boxes() {
		
		add_meta_box(
			"zibbra", // id
			__("Zibbra Meta", Zibbra_Plugin::LC_DOMAIN), // title
			array($this, "meta_box_content"), // callback
			"post", // post type
			"normal", // context
			"high" // priority
		);
		
	} // end function
	
	public function meta_box_content($post) {
		
		wp_nonce_field("zibbra_meta_box", "zibbra_meta_box_nonce");
		$value = get_post_meta($post->ID, "_zibbra_product_url", true);
		
		echo "<label for=\"zibbra_product_url\">";
		echo __("URL to the product page", Zibbra_Plugin::LC_DOMAIN);
		echo ":</label>&nbsp;";
		echo "<input type=\"url\" id=\"zibbra_product_url\" name=\"zibbra_product_url\" value=\"".esc_attr($value)."\" size=\"100%\" />";
		
	} // end function
	
	public function save_post($post_id) {
		
		// Check if our nonce is set
		
		if(!isset($_POST['zibbra_meta_box_nonce'])) {
			
			return;
			
		} // end if
		
		// Verify that the nonce is valid
		
		if(!wp_verify_nonce($_POST['zibbra_meta_box_nonce'], "zibbra_meta_box")) {
			
			return;
			
		} // end if
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything
		
		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
			
			return;
			
		} // end if
		
		// Check the user's permissions
		
		if(isset($_POST['post_type']) && $_POST['post_type']=="post") {
		
			if(!current_user_can("edit_post", $post_id)) {
				
				return;
				
			} // end if
			
		}else{
			
			return;
			
		} // end if
		
		// Make sure that it is set
		
		if(!isset($_POST['zibbra_product_url'])) {
			
			return;
			
		} // end if
		
		// Sanitize user input
		
		$value = sanitize_text_field($_POST['zibbra_product_url']);
		
		// Update the meta field in the database
		
		update_post_meta($post_id, "_zibbra_product_url", $value);
		
	} // end function	
	
	public function login_url($login_url, $redirect=null) {

		if(site_url().$_SERVER['REQUEST_URI'] == admin_url()) {
		
			return $login_url;
			
		} // end if
		
		return $login_url;
		
	} // end function
	
	public function register_url($register_url, $redirect=null) {
		
		$url = "/zibbra/register/";
		
		if(!empty($redirect)) {
			
			$url .= "?redirect_to=".$redirect;
			
		} // end if
		
		return site_url($url);
		
	} // end function
	
	public function lostpassword_url($lostpassword_url, $redirect=null) {
		
		return site_url("/zibbra/reset/");
		
	} // end function
	
	public function body_class($classes) {
		
		if($this->module instanceof Zibbra_Plugin_Module_Abstract) {
		
			$classes[] = "zibbra-".$this->module->getModuleName();
		
		} // end if
		
		return $classes;
		
	} // end function
	
	public function register_ga() {
		
		if(!empty($this->ga)) {
		
			echo $this->ga;
		
		} // end if
		
	} // end function

    public function register_fb() {

        if(!empty($this->fb)) {

            echo $this->fb;

        } // end if

    } // end function
	
	private function flush_rewrite_rules() {
		
		global $wp_rewrite;
		
		$wp_rewrite->flush_rules();
		
	} // end function
	
	private function remove_rewrite_rules() {

		remove_action("generate_rewrite_rules", array($this, "generate_rewrite_rules"));
		
	} // end function
	
} // end class

?>
