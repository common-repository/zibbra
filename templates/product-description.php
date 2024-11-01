<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<div id="zibbra-product-description">
	<h3 class="collapse-header">
		<a href="#collapse-description" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-description">
			<span class="title"><?php echo __("Description", Zibbra_Plugin::LC_DOMAIN); ?></span>
			<span class="icon icon-arrow-up"></span>
		</a>
	</h3>
	<div id="collapse-description" class="collapse in">
		<?php echo $product->getLongDescription(); ?>
	</div>
</div>