<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

?>
<?php get_zibbra_header(__("Customer Login", Zibbra_Plugin::LC_DOMAIN)); ?>
		
	<div id="zibbra-login">
	
			<?php

			$params = array(
				"redirect"=>isset($_GET['return_to']) ? esc_url($_GET['return_to'], ['http','https']) : site_url("/zibbra/account/"),
				"form_id"=>"zibbra_login_form",
				"label_username"=>__("E-mail", Zibbra_Plugin::LC_DOMAIN),
				"label_password"=>__("Password", Zibbra_Plugin::LC_DOMAIN),
				"label_remember"=>__("Remember me", Zibbra_Plugin::LC_DOMAIN),
				"label_log_in"=>__("Log In", Zibbra_Plugin::LC_DOMAIN),
				"remember"=>true
			);

			wp_login_form($params);

			?>
	
	</div>

<?php get_zibbra_footer(); ?>