<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

define("GENERATE_PASSWORD", get_option("zibbra_register_generate_password","N")=="Y");
define("SHIPPING_FIRST", get_option("zibbra_register_shipping_first","N")=="Y");

global $z_query;
$contact = $z_query->get("contact", null);
$return = $z_query->get("return", isset($_GET['return']) ? esc_url($_GET['return'], ['http', 'https']) : site_url("/"));

?>
<?php get_zibbra_header(__("Create a new account", Zibbra_Plugin::LC_DOMAIN)); ?>

	<div id="zibbra-register">
		<form method="post" id="zibbra_registration_form">
	
			<!-- Account information -->
			<section class="zibbra-register-account">
				<h4><?php echo __("Account Information", Zibbra_Plugin::LC_DOMAIN); ?></h4>
                <div class="form-group">
                    <label for="contact_email" class="required"><?php echo __("E-mail", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
                    <span class="info"><input type="email" id="contact_email" name="contact[email]" value="<?php echo isset($contact) ? $contact->getEmail() : ""; ?>" /></span>
                    <span class="info-text"><?php echo __("Please enter your email adress", Zibbra_Plugin::LC_DOMAIN); ?></span>
                </div>
                <?php if(!GENERATE_PASSWORD): ?>
                    <div class="form-group">
                        <label for="account_password" class="required"><?php echo __("Password", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
                        <span class="info"><input id="account_password" type="password" name="account[password]" id="password"></span>
                        <span class="info-text"><?php echo __("Your password must be at least 8 to 20 characters long and contain at least one letter and one number or a special character", Zibbra_Plugin::LC_DOMAIN); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="account_confirm_password" class="required"><?php echo __("Confirm password", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
                        <span class="info"><input id="account_confirm_password" type="password" name="account[confirm_password]"></span>
                        <span class="info-text"><?php echo __("Please repeat the password", Zibbra_Plugin::LC_DOMAIN); ?></span>
                    </div>
                <?php endif; ?>
			</section>
			<!-- End account section information -->
			
			<section class="zibbra-register-company-contact">
				<?php get_template_part("register", "contact"); ?>
				<?php get_template_part("register", "company"); ?>
				<div class="clear"></div>
			</section>
			
			<section class="zibbra-register-address">
				<?php if(SHIPPING_FIRST): ?>
					<?php get_template_part("register", "address-shipping"); ?>
					<?php get_template_part("register", "address-billing"); ?>
				<?php else: ?>
					<?php get_template_part("register", "address-billing"); ?>
					<?php get_template_part("register", "address-shipping"); ?>
				<?php endif; ?>
				<div class="clear"></div>
			</section>

            <?php Zibbra_Plugin_Recaptcha::showRecaptcha(); ?>
	
			<div class="btn-toolbar">
				<?php echo wp_nonce_field("do_register",Zibbra_Plugin::FORM_ACTION); ?>
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<input type="submit" name="submit" class="btn btn-primary" value="<?php echo __("Create account", Zibbra_Plugin::LC_DOMAIN); ?>" />
				<span>&nbsp;</span>
				<a href="<?php echo $return; ?>" class="btn btn-secundary"><?php echo __("Cancel", Zibbra_Plugin::LC_DOMAIN); ?></a>
			</div>
	
		</form>
	</div>

<?php get_zibbra_footer(); ?>