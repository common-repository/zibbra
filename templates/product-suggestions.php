<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<div id="zibbra-product-suggestions">
	<h3 class="collapse-header">
		<a href="#collapse-suggestions" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-suggestions"><?php echo __("Suggestions", Zibbra_Plugin::LC_DOMAIN); ?><span class="icon icon-arrow-up"></span></a>
	</h3>
	<div id="collapse-suggestions" class="collapse in">
		<div class="zibbra-product-suggestions">
			<ul>
				<?php foreach($product->getSuggestions() as $suggestion): ?>
					<?php $link = site_url("/zibbra/product/".$suggestion->getSlug()."/"); ?>
					<li id="zibbra-product-<?php echo $suggestion->getProductid(); ?>" class="zibbra-product-suggestion">
						<h3 class="name"><?php echo $suggestion->getName(); ?></h3>
						<?php if($suggestion->hasImages()): ?>
							<div class="image">
								<a href="<?php echo $link; ?>" class="zibbra-suggestion-link"><img src="<?php echo $suggestion->getFirstImage()->getPath(); ?>" border="0" /></a>
							</div>
						<?php endif; ?>
						<p class="description"><?php echo substr($suggestion->getShortDescription(),0,50); ?></p>
						<p class="price"><?php echo $suggestion->getValutaSymbol(); ?>&nbsp;<?php echo number_format($suggestion->getPrice(),2,",",""); ?></p>
						<a href="<?php echo $link; ?>" class="zibbra-suggestion-link btn btn-primary"><div class="icon"></div><span><?php echo __("View product", Zibbra_Plugin::LC_DOMAIN); ?></span></a>
						<div class="clearfix"></div>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
		<script>
		<?php foreach($product->getSuggestions() as $position=>$suggestion): ?>
			Zibbra.Product.registerProduct({
				"id": "<?php echo $suggestion->getProductid(); ?>",
				"sku": "<?php echo $suggestion->getCode(); ?>",
				"name": "<?php echo $suggestion->getName(); ?>",
				"price": "<?php echo number_format($suggestion->getPrice(),2,".",""); ?>",
				"position": <?php echo $position; ?>
			});
		<?php endforeach; ?>
		</script>
	</div>
</div>