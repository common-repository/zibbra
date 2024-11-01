<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$customer = z_get_customer();

?>

<?php get_zibbra_header(__("My Account", Zibbra_Plugin::LC_DOMAIN)); ?>
	
	<div id="zibbra-account">
		<div class="zibbra-account-details">
			<?php get_template_part("account", "details"); ?>
		</div>
		<div class="zibbra-account-orders">
			<?php get_template_part("account", "orders"); ?>
		</div>
		<div class="zibbra-account-invoices">
			<?php get_template_part("account", "invoices"); ?>
		</div>
	</div>
	
<?php get_zibbra_footer(); ?>