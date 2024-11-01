<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<?php if(!empty($title)): ?>
	<?php echo $args['before_title'].$title.$args['after_title']; ?>
<?php endif; ?>
<div id="zibbra-bestsellers">
	<ul>
		<?php foreach($bestsellers as $product): ?>
			<li>
				<div id="zibbra-product-<?php echo $product->getProductid(); ?>" class="zibbra-bestseller">
					<?php if($product->hasImages()): ?>
						<p class="image" align="center">
							<a href="<?php echo site_url("/zibbra/product/".$product->getSlug()."/"); ?>" class="zibbra-bestseller-link img-responsive"><img src="<?php echo $product->getFirstImage()->getPath(); ?>" border="0" /></a>
						</p>
					<?php endif; ?>
					<div class="info">
						<h4><?php echo strip_tags($product->getName()); ?></h4>
						<p class="description"><?php echo substr(strip_tags($product->getShortDescription()),0,50); ?></p>
						<p class="price"><?php echo $product->getValutaSymbol(); ?>&nbsp;<?php echo number_format($product->getPrice(),2,",",""); ?></p>
						<a href="<?php echo site_url("/zibbra/product/".$product->getSlug()."/"); ?>" class="zibbra-bestseller-link btn btn-primary"><?php echo __("View product", Zibbra_Plugin::LC_DOMAIN); ?></a>
						<div class="clearfix"></div>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<script>
jQuery(document).ready(function() {
<?php foreach($bestsellers as $position=>$product): ?>
	Zibbra.Bestsellers.registerProduct({
		"id": "<?php echo $product->getProductid(); ?>",
		"sku": "<?php echo $product->getCode(); ?>",
		"name": "<?php echo $product->getName(); ?>",
		"position": <?php echo $position; ?>
	});
<?php endforeach; ?>
	zibbra.get("bestsellers").initAnalytics();
}); // end ready
</script>
<?php echo $args['after_widget']; ?>