<?php
/**
 * @var ZPaymentAdapter[] $payment_adapters
 * @var ZPaymentAdapter $current_payment_adapter
 */
defined("ZIBBRA_BASE_DIR") or die("Restricted access");
?>
<div id="zibbra-checkout-payment">
	<?php if(count($payment_adapters)>0): ?>
		<fieldset class="radio">
			<?php foreach($payment_adapters as $index=>$payment_adapter): ?>
				<?php

					$price = $payment_adapter->getPrice();
					$value = $payment_adapter->getId();
					$label = $payment_adapter->getName();
					$checked = $current_payment_adapter->getId() == $value;

					if($price>0) {

						$label .= " (&euro;&nbsp;".number_format($price,2,",","").")";

					} // end if

				?>
				<input type="radio" id="checkout_payment_adapter_<?php echo $value; ?>" name="checkout[payment_adapter]" value="<?php echo $value; ?>"<?php echo $checked ? " checked=\"checked\"" : "" ?> />
				<label for="checkout_payment_adapter_<?php echo $value; ?>">
					<div class="radio-title"><?php echo $label; ?></div>
					<?php if($payment_adapter->hasDescription()): ?>
						<div class="radio-description"><?php echo $payment_adapter->getDescription(); ?></div>
					<?php endif; ?>
				</label>
			<?php endforeach; ?>
		</fieldset>
	<?php else: ?>
		<?php if(empty($customer)): ?>
			<p><?php echo __("No payment methods found.", Zibbra_Plugin::LC_DOMAIN); ?><br /><?php echo __("Please login first.", Zibbra_Plugin::LC_DOMAIN); ?></p>
		<?php else: ?>
			<p><?php echo __("No payment methods found.", Zibbra_Plugin::LC_DOMAIN); ?><br /><?php echo __("Please contact us for a custom solution.", Zibbra_Plugin::LC_DOMAIN); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div>