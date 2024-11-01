<?php
/**
 * Catalog product page
 *
 * @package Zibbra
 */
defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$in_stock = $product->isInStock();
$allow_order = $product->allowBackorder() || (!$product->allowBackorder() && $in_stock);
$product_url = site_url("/zibbra/product/".$product->getSlug()."/");

?>
<div class="zibbra-catalog-product-container">
	<div id="zibbra-product-<?php echo $product->getProductid(); ?>" class="zibbra-catalog-product" itemscope itemtype="http://schema.org/Product">
		<h2 class="zibbra-catalog-product-name"><a href="<?php echo $product_url; ?>" itemprop="name" class="zibbra-product-link"><?php echo $product->getName(); ?></a></h2>
		<h3 class="zibbra-catalog-product-code"><?php echo $product->getCode(); ?></h3>
		<p class="zibbra-catalog-product-description" itemprop="description"><?php echo strip_tags($product->getShortDescription()); ?></p>
		<div class="zibbra-catalog-product-image">
			<?php if($product->hasImages()): ?>
				<?php
				
				$image = $product->getImageByIndex(ZIBBRA_IMAGE_INDEX);
				
				?>
				<a href="<?php echo $product_url; ?>" class="zibbra-product-link">
					<img src="<?php echo $image->getPath(THUMBNAIL_SIZE); ?>" border="0" itemprop="image" class="img-responsive" />
				</a>
			<?php endif; ?>
		</div>
		<div class="zibbra-catalog-product-buy">
			<?php if(ZIBBRA_SHOW_STOCK && (ZIBBRA_SHOW_STOCK_BACKORDER || $in_stock)): ?>
				<p class="zibbra-catalog-product-stock">
					<?php if(ZIBBRA_SHOW_STOCK_QUANTITY): ?>
						<label><?php echo __("Stock", Zibbra_Plugin::LC_DOMAIN); ?>:</label><span class="<?php echo $product->isInStock() ? "in_stock" : "not_in_stock"; ?>"><span class="icon"></span><?php echo $product->getStock(); ?></span>
					<?php else: ?>
						<label><?php echo __("Availability", Zibbra_Plugin::LC_DOMAIN); ?>:</label><span class="<?php echo $product->isInStock() ? "in_stock" : "not_in_stock"; ?>"><span class="icon"></span><?php echo $product->isInStock() ? __("In stock", Zibbra_Plugin::LC_DOMAIN) : __("Not in stock", Zibbra_Plugin::LC_DOMAIN); ?></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<div class="zibbra-catalog-product-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<?php if($product->hasDiscount()): ?>
					<?php
						$from = $product->getValutaSymbol()." ".number_format($product->getBasePrice(),2,",","");
						$for =  $product->getValutaSymbol()." ".number_format($product->getPrice(),2,",","");
						echo sprintf(__("<small>From <s>%s</s> for </small>%s"), $from, $for);
					?>
				<?php else: ?>
					<span itemprop="price">
						<span class="valuta"><?php echo $product->getValutaSymbol(); ?>&nbsp;</span>
						<span class="price"><?php echo number_format($product->getPrice(),2,",",""); ?></span>
					</span>
				<?php endif; ?>
			</div>
			<?php if(BUTTON_ADD_TO_CART): ?>
				<form method="post" name="zibbra-product-form" id="zibbra-product-form">
					<?php echo wp_nonce_field("add_product",Zibbra_Plugin::FORM_ACTION); ?>
					<input type="hidden" name="id" value="<?php echo $product->getProductid(); ?>" />
					<input type="submit" class="btn btn-primary zibbra-catalog-product-add-to-cart<?php if(!$allow_order): ?> disabled<?php endif; ?>" value="<?php echo __("Add to cart", Zibbra_Plugin::LC_DOMAIN); ?>"<?php if(!$allow_order): ?> disabled="disabled"<?php endif; ?> />
				</form>
			<?php else: ?>
				<a href="<?php echo $product_url; ?>" class="btn btn-primary"><?php echo __("View product", Zibbra_Plugin::LC_DOMAIN); ?></a>
			<?php endif; ?>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
	<script>
	Zibbra.Catalog.registerProduct({
		"id": "<?php echo $product->getProductid(); ?>",
		"sku": "<?php echo $product->getCode(); ?>",
		"name": "<?php echo $product->getName(); ?>",
		"brand": "<?php echo $product->hasManufacturer() ? $product->getManufacturer()->getName() : ""; ?>",
		"price": <?php echo number_format($product->getPrice(),2,".",""); ?>,
		"position": <?php echo $product->getPosition(); ?>
	});
	</script>
</div>