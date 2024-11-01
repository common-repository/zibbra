<?php

class Zibbra_Plugin_Widget_Newsletter extends Zibbra_Plugin_Widget_Abstract {
	
	public function __construct() {

		$this->name = __("Zibbra Newsletter", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Newsletter subscription for the Zibbra E-Marketing module.", Zibbra_Plugin::LC_DOMAIN);

		parent::__construct();
		
		add_action("wp_ajax_zibbra_newsletter_subscribe", array($this, "do_subscribe"));
		add_action("wp_ajax_nopriv_zibbra_newsletter_subscribe", array($this, "do_subscribe"));
		
	} // end function
	
	public function form($instance) {
			
		$title = isset($instance['title']) ? $instance['title'] : __("Subscribe", Zibbra_Plugin::LC_DOMAIN);
		$description = isset($instance['description']) ? $instance['description'] : __("Subscribe to our newsletter", Zibbra_Plugin::LC_DOMAIN);
		$input = isset($instance['input']) ? $instance['input'] : __("Your e-mail", Zibbra_Plugin::LC_DOMAIN);
		$button = isset($instance['button']) ? $instance['button'] : __("Subscribe", Zibbra_Plugin::LC_DOMAIN);
		$icon = isset($instance['icon']) ? $instance['icon'] : "";
		
		include(sprintf("%s/widgets/newsletter-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		$instance = array();
				
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";
		$instance['description'] = (!empty($new_instance['description'])) ? strip_tags($new_instance['description']) : "";
		$instance['input'] = (!empty($new_instance['input'])) ? strip_tags($new_instance['input']) : "";
		$instance['button'] = (!empty($new_instance['button'])) ? strip_tags($new_instance['button']) : "";
		$instance['icon'] = (!empty($new_instance['icon'])) ? strip_tags($new_instance['icon']) : "";
	
		return $instance;
		
	} // end function
	
	public function widget($args, $instance) {

		wp_enqueue_script("wp-plugin-zibbra-newsletter", plugins_url("jscripts/widget_newsletter.js",ZIBBRA_BASE_DIR."/jscripts"));
		
		$vars = array(
			"title" => apply_filters("widget_title", $instance['title']),
			"description" => $instance['description'],
			"input" => $instance['input'],
			"button" => $instance['button'],
			"icon" => $instance['icon']
		);
		
		$this->template_include("newsletter", $args, $vars);
		
	} // end function
	
	public function do_subscribe() {

	    $json = new stdClass();
		$json->status = false;
		$json->message = __("An error occured, please try again", Zibbra_Plugin::LC_DOMAIN);
		
		if(!empty($_POST) && isset($_POST[Zibbra_Plugin::FORM_ACTION]) && isset($_POST['email']) && wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "subscribe")) {
			
			$email = sanitize_email(trim($_POST['email']));
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			
				$result = ZEmarketing::subscribe($email);
				$json = new StdClass();
			
				if($result instanceof ZApiError) {
					
					$json->status = false;
					
					switch($result->getCode()) {
					
						case "INVALID_PARAM_VALUE": $json->message = __("Invalid email, please try again", Zibbra_Plugin::LC_DOMAIN);break;
						default: $json->message = __("An error occured, please try again", Zibbra_Plugin::LC_DOMAIN);break;
					
					} // end switch
				
				}else{
					
					$json->status = true;
					$json->message = __("Thank you for subscribing!", Zibbra_Plugin::LC_DOMAIN);
					
				} // end if
			
			}else{

				$json->status = false;
				$json->message = __("Invalid email, please try again", Zibbra_Plugin::LC_DOMAIN);
				
			} // end if
		
		} // end if

		header("Content-Type: application/json");
		echo json_encode($json);
		exit;
		
	} // end function
	
} // end class

?>