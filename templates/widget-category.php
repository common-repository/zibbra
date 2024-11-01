<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$class = "col-xs-12";
$cols = 1;

if($orientation === Zibbra_Plugin_Widget_Category::ORIENTATION_HORIZONTAL) {

	switch($maxval) {

		case 2: $class .= " col-md-6"; $cols = 2;break;
		case 3: $class .= " col-md-4"; $cols = 3;break;
		case 4: $class .= " col-md-3"; $cols = 4;break;
		case 6: $class .= " col-md-2"; $cols = 6;break;
		case 8: $class .= " col-md-3"; $cols = 4;break;
		case 12: $class .= " col-md-2"; $cols = 6;break;

	} // end switch

} // end if

?>
<?php echo $args['before_widget']; ?>
<?php if(!empty($title)): ?>
	<?php echo $args['before_title'].$title.$args['after_title']; ?>
<?php endif; ?>
<div id="zibbra-category">
	<div class="row">
		<?php foreach($products as $i=>$product): ?>
			<div class="<?php echo $class; ?>">
				<div class="thumbnail" id="zibbra-product-<?php echo $product->getProductid(); ?>">
					<?php if($product->hasImages()): ?>
						<a href="<?php echo site_url("/zibbra/product/".$product->getSlug()."/"); ?>">
							<img src="<?php echo $product->getFirstImage()->getPath($thumbsize); ?>" alt="<?php echo strip_tags($product->getName()); ?>">
						</a>
					<?php endif; ?>
					<div class="caption text-center">
						<h3 class="name"><?php echo strip_tags($product->getName()); ?></h3>
						<?php if($description): ?>
							<p class="description"><?php echo strip_tags($product->getShortDescription()); ?></p>
						<?php endif; ?>
						<p class="price"><?php echo $product->getValutaSymbol(); ?>&nbsp;<?php echo number_format($product->getPrice(),2,",",""); ?></p>
						<a href="<?php echo site_url("/zibbra/product/".$product->getSlug()."/"); ?>" class="btn btn-primary" role="button"><?php echo __("View product", Zibbra_Plugin::LC_DOMAIN); ?></a>
					</div>
				</div>
			</div>
            <?php if((($i + 1) % $cols) === 0): ?>
                <div class="clearfix"></div>
            <?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
<script>
jQuery(document).ready(function() {
<?php foreach($products as $position=>$product): ?>
	Zibbra.Category.registerProduct({
		"id": "<?php echo $product->getProductid(); ?>",
		"sku": "<?php echo $product->getCode(); ?>",
		"name": "<?php echo $product->getName(); ?>",
		"position": <?php echo $position; ?>
	});
<?php endforeach; ?>
	zibbra.get("category").initAnalytics();
}); // end ready
</script>
<?php echo $args['after_widget']; ?>