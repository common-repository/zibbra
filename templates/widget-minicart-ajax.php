<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

if(!isset($cart)) {
	
	$cart = new ZCart();
	
} // end if
	
$items = $cart->getItems();
$count = count($items);
$symbol = $cart->getValutaSymbol();

?>
<div class="zibbra-minicart-summary">
	<div class="icon icon-cart glyphicon glyphicon-shopping-cart" data-after="0"></div>
	<div class="count"><?php echo $count; ?>&nbsp;<?php echo $count==1 ? __("item", Zibbra_Plugin::LC_DOMAIN) : __("items", Zibbra_Plugin::LC_DOMAIN); ?></div>
	<div class="price"><?php echo $symbol; ?>&nbsp;<?php echo number_format($cart->getTotalAmount(false),2,",",""); ?></div>
	<div class="icon icon-arrow-down glyphicon glyphicon-chevron-down"></div>
	<button onclick="location.href='<?php echo site_url("/zibbra/checkout/"); ?>';"><?php echo __("Checkout", Zibbra_Plugin::LC_DOMAIN); ?></button>
	<div class="clearfix"></div>
</div>
<?php if(!$cart->isEmpty()): ?>
	<div class="zibbra-minicart-details"<?php if($popup): ?> style="display:none;"<?php endif; ?>>
		<div>
			<div class="arrow"></div>
			<ul>
				<?php foreach($items as $item): ?>
					<li>
						<?php if($links): ?>
							<?php echo $item->getQuantity(); ?>&nbsp;x&nbsp;<a href="<?php echo site_url("/zibbra/product/".ZProduct::generateSlug($item->getProductid(),$item->getDescription())); ?>"><?php echo $item->getDescription(); ?></a>
						<?php else: ?>
							<?php echo $item->getQuantity(); ?>&nbsp;x&nbsp;<?php echo $item->getDescription(); ?></td>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="btn btn-primary" href="<?php echo site_url("/zibbra/cart/"); ?>"><?php echo __("View Cart", Zibbra_Plugin::LC_DOMAIN); ?></a>
		</div>
	</div>
<?php endif; ?>