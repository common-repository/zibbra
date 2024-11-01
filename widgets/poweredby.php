<?php

class Zibbra_Plugin_Widget_Poweredby extends Zibbra_Plugin_Widget_Abstract {
	
	public function __construct() {

		$this->name = __("Powered by Zibbra", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Show a small label that this webshop is powered by Zibbra", Zibbra_Plugin::LC_DOMAIN);

		parent::__construct();
		
	} // end function
	
	public function widget($args, $instance) {
		
		$this->template_include("poweredby", $args);
		
	} // end function
	
} // end class

?>