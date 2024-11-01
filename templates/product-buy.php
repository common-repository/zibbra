<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$allow_order = ZIBBRA_ALLOW_BACKORDER || (!ZIBBRA_ALLOW_BACKORDER && ZIBBRA_IN_STOCK);

?>
<div class="zibbra-product-buy" itemprop="offers" itemscope itemtype="http://schema.org/Offer">			
	<div class="zibbra-product-price" itemprop="price">
		<?php if($product->hasDiscount()): ?>
			<?php
				$from = $product->getValutaSymbol()." ".number_format($product->getBasePrice(),2,",","");
				$for =  $product->getValutaSymbol()." ".number_format($product->getPrice(),2,",","");
				echo sprintf(__("<small>From <s>%s</s> for </small>%s"), $from);
			?>
		<?php else: ?>
			<?php echo $product->getValutaSymbol(); ?>&nbsp;<span><?php echo number_format($product->getPrice(),2,",",""); ?></span>
		<?php endif; ?>
	</div>
	<div class="form-inline">
		<?php if(ZIBBRA_QUANTITY_SELECTOR): ?>
			<div class="form-group">
				<label for="zibbra-product-quantity" class="sr-only"><?php echo __("Quantity", Zibbra_Plugin::LC_DOMAIN); ?></label>
				<input id="zibbra-product-quantity" name="quantity" type="number" min="1" max="9999" step="1" value="1" />
			</div>
		<?php endif; ?>
		<input type="submit" class="btn btn-primary zibbra-product-add-to-cart<?php if(!$allow_order): ?> disabled<?php endif; ?>" value="<?php echo __("Add to cart", Zibbra_Plugin::LC_DOMAIN); ?>"<?php if(!$allow_order): ?> disabled="disabled"<?php endif; ?> />
	</div>
</div>