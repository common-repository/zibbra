<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$args = array(
	"redirect"=>site_url("/zibbra/checkout/"),
	"form_id"=>"zibbra-checkout-login-form",
	"label_username"=>__("Username", Zibbra_Plugin::LC_DOMAIN),
	"label_password"=>__("Password", Zibbra_Plugin::LC_DOMAIN),
	"label_remember"=>__("Remember me", Zibbra_Plugin::LC_DOMAIN),
	"label_log_in"=>__("Log In", Zibbra_Plugin::LC_DOMAIN),
	"remember"=>true
);

wp_login_form($args);

?>