<?php
defined("ZIBBRA_BASE_DIR") or die("Restricted access");
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="zibbra-cart">
	<thead>
		<tr>
			<th><?php echo __("Product", Zibbra_Plugin::LC_DOMAIN); ?></th>
			<th class="right"><?php echo __("Total", Zibbra_Plugin::LC_DOMAIN); ?></th>
			<th class="right"><?php echo __("VAT", Zibbra_Plugin::LC_DOMAIN); ?></th>
		</tr>
	</thead>
	<tbody>			
		<?php foreach($cart->getItems() as $item): ?>		
			<tr>				
				<td>
					<span><?php echo $item->getQuantity(); ?>&nbsp;x&nbsp;</span>
					<?php if(LINK_PRODUCTS): ?>
						<a href="<?php echo site_url("/zibbra/product/".ZProduct::generateSlug($item->getProductid(),$item->getDescription())."/"); ?>">
							<span><?php echo $item->getDescription(); ?></span>
							<?php if($item->hasDiscount()): ?>
								<span class="discount">&nbsp;(<s><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getAmount()*$item->getQuantity(),2,",",""); ?></s>)</span>
							<?php endif; ?>
						</a>
					<?php else: ?>
						<span><?php echo $item->getDescription(); ?></span>
						<?php if($item->hasDiscount()): ?>
							<span class="discount">&nbsp;(<s><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getAmount()*$item->getQuantity(),2,",",""); ?></s>)</span>
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<td class="right">
					<?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getTotal(),2,",",""); ?>
				</td>
				<td class="right"><?php echo $item->getVat()*100; ?>%</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<table cellpadding="0" cellspacing="4" border="0" class="zibbra-totals">
	<tbody>
		<?php if($cart->hasDiscount()): $discount = $cart->getDiscount(); ?>
			<tr>
				<td><?php echo __("Total cart", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
				<td class="right" id="zibbra-checkout-total"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalCart(),2,",",""); ?></td>
			</tr>
			<tr>
				<td><?php echo __("Discounts", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
				<td class="right" id="zibbra-checkout-discount">-&nbsp;<?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalDiscount(),2,",",""); ?></td>
			</tr>
		<?php endif; ?>
		<tr>						
			<td><?php echo __("Total VAT excl.", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
			<td class="right" id="zibbra-checkout-total_excl"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalExcl(),2,",",""); ?></td>
		</tr>
		<tr>
			<td><?php echo __("Total VAT", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
			<td class="right" id="zibbra-checkout-total_vat"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalVat(),2,",",""); ?></td>
		</tr>
		<?php if($current_shipping_method): ?>
			<tr<?php if($current_shipping_method->getPrice()==0): ?> style="display:none;"<?php endif; ?>>
				<td><?php echo __("Shipping cost", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
				<td class="right" id="zibbra-checkout-shipping_cost"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($current_shipping_method->getPriceVatIncl(),2,",",""); ?></td>
			</tr>
		<?php endif; ?>
		<?php if($current_payment_adapter): ?>
			<tr<?php if($current_payment_adapter->getPrice()==0): ?> style="display:none;"<?php endif; ?>>
				<td><?php echo __("Payment cost", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
				<td class="right" id="zibbra-checkout-payment_cost""><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($current_payment_adapter->getPrice(),2,",",""); ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<td><?php echo __("Total VAT incl.", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
			<td class="right" id="zibbra-checkout-total_incl"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalAmount(),2,",",""); ?></td>
		</tr>				
	</tbody>				
</table>

<div class="clearfix"></div>