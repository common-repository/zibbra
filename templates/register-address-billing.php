<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;

$z_query->set("address_type", "billing");

if($billing_address = $z_query->get("billing_address", false)) {

	$z_query->set("address", $billing_address);

} // end if
	
?>
<!-- Billing Address -->
<section class="zibbra-address-billing">
	<?php if(SHIPPING_FIRST && !isset($edit)): ?>
		<div class="toggle">
			<input type="checkbox" id="toggle_same_address" name="billing[toggle]" value="Y" checked="checked" />
			<label for="toggle_same_address"><?php echo __("Same as shipping address", Zibbra_Plugin::LC_DOMAIN); ?></label>
		</div>
	<?php endif; ?>
	<h4><?php echo __("Billing Address", Zibbra_Plugin::LC_DOMAIN); ?></h4>
	<?php if(SHIPPING_FIRST): ?>
		<div id="hidden_form_shipping"<?php if(!isset($edit)): ?> style="display:none;"<?php endif; ?>>
			<?php get_zibbra_template_part("register", "address"); ?>
		</div>
	<?php else: ?>
		<?php get_zibbra_template_part("register", "address"); ?>
	<?php endif; ?>
</section>
<!-- End Billing Address -->