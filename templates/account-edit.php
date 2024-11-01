<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;

$customer = z_get_customer();
$edit = $z_query->get("edit");
$return = $z_query->get("return",null);

?>
<?php get_zibbra_header(); ?>
		
	<header class="entry-header">
		<h1 class="entry-title"><?php echo __("My Account", Zibbra_Plugin::LC_DOMAIN); ?></h1>
	</header>
	
	<form method="post" id="zibbra_account_form" class="zibbra-account-form">
		
		<?php
		
			switch($edit) {
				
				case "customer": {

					get_zibbra_template_part("register", "contact");
					get_zibbra_template_part("register", "company");
					
				};break;
				
				case "billing": {

					get_zibbra_template_part("register", "address-billing");
					
				};break;
				
				case "shipping": {

					get_zibbra_template_part("register", "address-shipping");
					
				};break;
				
			} // end switch
		
		?>
			
		<div class="btn-toolbar">
			<?php echo wp_nonce_field("update_account",Zibbra_Plugin::FORM_ACTION); ?>
			<input type="hidden" name="section" value="<?php echo $edit; ?>" />
			<?php if(isset($_GET['return'])): ?>
			<input type="hidden" name="return" value="<?php echo esc_url($_GET['return'], ['http', 'https']); ?>" />
			<?php endif; ?>
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo __("Save Changes", Zibbra_Plugin::LC_DOMAIN); ?>" />
			<a href="<?php echo isset($_GET['return']) ? esc_url($_GET['return'], ['http', 'https']) : site_url("/zibbra/account/"); ?>" class="btn btn-secundary"><?php echo __("Cancel", Zibbra_Plugin::LC_DOMAIN); ?></a>
		</div>
		
		<script>
		Zibbra.Account.FORM = "<?php echo $edit; ?>";
		</script>
		
	</form>

<?php get_zibbra_footer(); ?>