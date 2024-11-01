<?php
/**
 * @var ZShippingMethod[] $shipping_methods
 * @var ZShippingMethod $current_shipping_method
 */
defined("ZIBBRA_BASE_DIR") or die("Restricted access");
?>
<div id="zibbra-checkout-shipping">
	<?php if(count($shipping_methods)>0): ?>
		<fieldset class="radio">
			<?php foreach($shipping_methods as $index=>$shipping_method): ?>
				<?php

					$price = $shipping_method->getPriceVatIncl();
					$value = $shipping_method->getShippingmethodid();
					$checked = $current_shipping_method->getShippingmethodid() == $value;
					$label = $shipping_method->getName();

					if($price>0) {

						$label .= " (&euro;&nbsp;".number_format($price,2,",","").")";

					} // end if

				?>
				<input type="radio" id="checkout_shipping_method_<?php echo $value; ?>" name="checkout[shipping_method]" value="<?php echo $value; ?>"<?php echo $checked ? "checked=\"checked\"" : "" ?> />
				<label for="checkout_shipping_method_<?php echo $value; ?>">
					<div class="radio-title"><?php echo $label; ?></div>
					<?php if($shipping_method->hasDescription()): ?>
						<div class="radio-description"><?php echo $shipping_method->getDescription(); ?></div>
					<?php endif; ?>
				</label>
			<?php endforeach; ?>
		</fieldset>
	<?php else: ?>
		<?php if(empty($customer)): ?>
			<p><?php echo __("No shipping methods found.", Zibbra_Plugin::LC_DOMAIN); ?><br /><?php echo __("Please login first.", Zibbra_Plugin::LC_DOMAIN); ?></p>
		<?php else: ?>
			<p><?php echo __("No shipping methods found.", Zibbra_Plugin::LC_DOMAIN); ?><br /><?php echo __("Please contact us for a custom quote.", Zibbra_Plugin::LC_DOMAIN); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div>