<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;

$orderid = $z_query->get("orderid");
$adapter = $z_query->get("adapter");
$host = $z_query->get("host");

?>
<?php get_zibbra_header(); ?>

<div id="payment-verify" class="container">
	<div class="text-center">
		<p>&nbsp;</p>
		<h4><?php echo __("Verifying your payment, please wait",Zibbra_Plugin::LC_DOMAIN); ?></h4>
		<p>&nbsp;</p>
		<p class="text-center"><i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></p>
		<p>&nbsp;</p>
		<p><a href="<?php echo site_url("/zibbra/payment/cancel/" . $adapter . "/" . $orderid . "/"); ?>"><?php echo __("Click here to cancel and try again",Zibbra_Plugin::LC_DOMAIN); ?></a></p>
		<p>&nbsp;</p>
	</div>
</div>
<script>
	jQuery(document).ready(function() {
		new Zibbra.Payment()
			.setOrderId(<?php echo $orderid; ?>)
			.setAdapter('<?php echo $adapter; ?>')
			.setHost('<?php echo $host; ?>')
			.check();
	});
</script>

<?php get_zibbra_footer(); ?>