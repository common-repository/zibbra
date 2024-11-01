<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$billing_address = $customer->getBillingAddress();

?>
<section>
	<h3>
		<span><?php echo __("Billing address", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</h3>
	<p><?php echo $billing_address->getStreet(); ?>&nbsp;<?php echo $billing_address->getStreetnr(); ?>&nbsp;<?php echo $billing_address->getBox(); ?><br /><?php echo $billing_address->getCountrycode(); ?>-<?php echo $billing_address->getZipcode(); ?>&nbsp;<?php echo $billing_address->getCity(); ?></p>
	<div class="clearfix"></div>
	<?php if(!defined("ZIBBRA_ACCOUNT_HIDE_BUTTONS")): ?>
		<div class="btn-toolbar">
			<a href="<?php echo site_url("/zibbra/account/billing/edit/".(isset($return) ? "?return=".$return : "")); ?>" class="btn btn-primary"><?php echo __("Edit", Zibbra_Plugin::LC_DOMAIN); ?></a>
		</div>
	<?php endif; ?>
	<div class="clearfix"></div>
</section>