<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$shipping_address = $customer->getShippingAddress();

?>
<section>
	<h3>
		<span><?php echo __("Shipping address", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</h3>
	<p><?php echo $shipping_address->getStreet(); ?>&nbsp;<?php echo $shipping_address->getStreetnr(); ?>&nbsp;<?php echo $shipping_address->getBox(); ?><br /><?php echo $shipping_address->getCountrycode(); ?>-<?php echo $shipping_address->getZipcode(); ?>&nbsp;<?php echo $shipping_address->getCity(); ?></p>
	<div class="clearfix"></div>
	<?php if(!defined("ZIBBRA_ACCOUNT_HIDE_BUTTONS")): ?>
		<div class="btn-toolbar">
			<a href="<?php echo site_url("/zibbra/account/shipping/edit/".(isset($return) ? "?return=".$return : "")); ?>" class="btn btn-primary"><?php echo __("Edit", Zibbra_Plugin::LC_DOMAIN); ?></a>
		</div>
	<?php endif; ?>
	<div class="clearfix"></div>
</section>