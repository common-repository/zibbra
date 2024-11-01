<?php

function get_zibbra_header($title = null) {
	
	include(ZIBBRA_BASE_DIR."/templates/header.php");
	
} // end function

function get_zibbra_footer() {
	
	include(ZIBBRA_BASE_DIR."/templates/footer.php");
	
} // end function

function get_zibbra_template_part($slug, $name = null) {
	
	global $z_query, $wp_query;
	
	$path_theme = TEMPLATEPATH."/zibbra_templates";
	$path_plugin = ZIBBRA_BASE_DIR."/templates";
	
	$templates = array();
	$name = (string) $name;
	
	if($name !== "") {
		
		$templates[] = $path_theme."/{$slug}-{$name}.php";
		$templates[] = $path_plugin."/{$slug}-{$name}.php";
	
	} // end if
	
	$templates[] = $path_theme."/{$slug}.php";
	$templates[] = $path_plugin."/{$slug}.php";
	
	$located = false;
	
	foreach($templates as $template) {
		
		if(!$template) continue;
		
		if(file_exists($template)) {
			
			$located = $template;
			break;
			
		} // end if
		
	} // end foreach
	
	if($located !== false) {
		
		$query_vars_backup = $wp_query->query_vars;
		
		$wp_query->query_vars = $z_query->query_vars;
		
		load_template($located, false);
		
		$wp_query->query_vars = $query_vars_backup;
		
	} // end if
	
	return $located;
	
} // end function

function is_zibbra() {
	
	global $wp_query;
	
	return isset($wp_query->is_zibbra) && $wp_query->is_zibbra===true;
	
} // end function

function is_zibbra_account() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Account;

} // end function

function is_zibbra_brand() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Brand;

} // end function

function is_zibbra_cart() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Cart;

} // end function

function is_zibbra_catalog() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Catalog;

} // end function

function is_zibbra_checkout() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Checkout;

} // end function

function is_zibbra_login() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Login;

} // end function

function is_zibbra_payment() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Payment;

} // end function

function is_zibbra_product() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Product;

} // end function

function is_zibbra_register() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Register;

} // end function

function is_zibbra_reset() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Reset;

} // end function

function is_zibbra_shipping() {

	return Zibbra_Plugin_Controller::getInstance()->getActiveModule() instanceof Zibbra_Plugin_Module_Shipping;

} // end function

?>