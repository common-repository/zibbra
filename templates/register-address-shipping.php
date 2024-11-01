<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;

$z_query->set("address_type", "shipping");

if($shipping_address = $z_query->get("shipping_address", false)) {

	$z_query->set("address", $shipping_address);

} // end if

?>
<!-- Shipping Address -->
<section class="zibbra-address-shipping">
	<?php if(!SHIPPING_FIRST && !isset($edit)): ?>
		<div class="toggle">
			<input type="checkbox" id="toggle_same_address" name="shipping[toggle]" value="Y" checked="checked" />
			<label for="toggle_same_address"><?php echo __("Same as billing address", Zibbra_Plugin::LC_DOMAIN); ?></label>
		</div>
	<?php endif; ?>
	<h4><?php echo __("Shipping Address", Zibbra_Plugin::LC_DOMAIN); ?></h4>
	<?php if(!SHIPPING_FIRST): ?>
	<div id="hidden_form_shipping"<?php if(!isset($edit)): ?> style="display:none;"<?php endif; ?>>
		<?php get_zibbra_template_part("register", "address"); ?>
	</div>
	<?php else: ?>
		<?php get_zibbra_template_part("register", "address"); ?>
	<?php endif; ?>
</section>
<!-- End Shipping Address -->
<?php if(SHIPPING_FIRST && !isset($edit)): ?>
	<script>
	Zibbra.Register.MODE_ADDRESS = Zibbra.Register.SHIPPING;
	</script>
<?php endif; ?>