<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$company = $customer->getCompany();
$contact = $customer->getPrimaryContact();

?>
<section>
	<h3>
		<span><?php echo __("Account information", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</h3>
	<dl class="zibbra-account">
		<dt><?php echo __("Contact", Zibbra_Plugin::LC_DOMAIN); ?>:</dt>
		<dd><?php echo $contact->getFirstname(); ?>&nbsp;<?php echo $contact->getLastname(); ?></dd>
		<?php if(($name = $company->getName())!==null && !empty($name)): ?>
			<dt><?php echo __("Company", Zibbra_Plugin::LC_DOMAIN); ?>:</dt>
			<dd><?php echo $name; ?></dd>
		<?php endif; ?>
		<dt><?php echo __("E-mail", Zibbra_Plugin::LC_DOMAIN); ?>:</dt>
		<dd><a href="mailto:<?php echo $contact->getEmail(); ?>" target="_blank"><?php echo $contact->getEmail(); ?></a></dd>
		<?php if(($phone = $contact->getPhone())!==null && !empty($phone)): ?>
			<dt><?php echo __("Phone", Zibbra_Plugin::LC_DOMAIN); ?>:</dt>
			<dd><?php echo $phone; ?></dd>
		<?php endif; ?>
	</dl>
	<div class="clearfix"></div>
	<?php if(!defined("ZIBBRA_ACCOUNT_HIDE_BUTTONS")): ?>
		<div class="btn-toolbar">
			<a href="<?php echo site_url("/zibbra/account/customer/edit/".(isset($return) ? "?return=".$return : "")); ?>" class="btn btn-primary"><?php echo __("Edit", Zibbra_Plugin::LC_DOMAIN); ?></a>
			<a href="<?php echo wp_logout_url(home_url()); ?>" class="btn btn-secundary" title="<?php echo __("Logout", Zibbra_Plugin::LC_DOMAIN); ?>"><?php echo __("Logout", Zibbra_Plugin::LC_DOMAIN); ?></a>
		</div>
	<?php endif; ?>
	<div class="clearfix"></div>
</section>