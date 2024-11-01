<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

/** @var Zibbra_Plugin_Query $z_query */
global $z_query;
$adapter = $z_query->get("adapter", null);
$orderid = $z_query->get("orderid", null);

?>
<?php get_zibbra_header(__("Shipping configuration", Zibbra_Plugin::LC_DOMAIN)); ?>
	
	<div id="zibbra-shipping">
			
		<?php get_zibbra_template_part("shipping", $adapter); ?>
				
		<div class="btn-toolbar">
					
			<a class="btn btn-primary" href="<?php echo site_url("/zibbra/shipping/cancel/" . $adapter . "/" . $orderid . "/"); ?>">
				<span><?php echo __("Cancel", Zibbra_Plugin::LC_DOMAIN); ?></span>
			</a>

		</div>
	
	</div>

<?php get_zibbra_footer(); ?>