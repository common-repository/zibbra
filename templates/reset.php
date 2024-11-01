<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

?>
<?php get_zibbra_header(__("Reset your password", Zibbra_Plugin::LC_DOMAIN)); ?>
		
	<div id="zibbra-reset">
		<form method="post" id="zibbra_reset_form">
	
			<section>
				<h4><?php echo __("Enter your e-mail address", Zibbra_Plugin::LC_DOMAIN); ?></h4>
				<div class="form-group">
					<label for="email" class="required"><?php echo __("E-mail", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
					<span class="info"><input id="email" type="email" name="email" /></span>
					<span class="info-text"><?php echo __("Enter the e-mail address of your account", Zibbra_Plugin::LC_DOMAIN); ?></span>
				</div>
			</section>

            <?php Zibbra_Plugin_Recaptcha::showRecaptcha(); ?>
	
			<div class="btn-toolbar">
				<?php echo wp_nonce_field("do_reset",Zibbra_Plugin::FORM_ACTION); ?>
				<input type="submit" name="submit" class="btn btn-primary" value="<?php echo __("Submit", Zibbra_Plugin::LC_DOMAIN); ?>" />
				<span>&nbsp;</span>
				<a href="<?php echo site_url("/"); ?>"><?php echo __("Cancel", Zibbra_Plugin::LC_DOMAIN); ?></a>
			</div>
	
		</form>
	</div>

<?php get_zibbra_footer(); ?>