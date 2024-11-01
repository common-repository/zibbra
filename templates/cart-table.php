<table>
    <thead>
        <tr>
            <th><?php echo __("Product", Zibbra_Plugin::LC_DOMAIN); ?></th>
            <th class="center"><?php echo __("Quantity", Zibbra_Plugin::LC_DOMAIN); ?></th>
            <th class="right"><?php echo __("Amount", Zibbra_Plugin::LC_DOMAIN); ?></th>
            <th class="right"><?php echo __("Total", Zibbra_Plugin::LC_DOMAIN); ?></th>
            <?php if(!SHOW_INCL_VAT): ?>
                <th class="right"><?php echo __("VAT", Zibbra_Plugin::LC_DOMAIN); ?></th>
            <?php endif; ?>
            <th class="actions">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($cart->getItems() as $item): ?>
	        <?php if(!$item->isAddon()): ?>
		        <?php table_row($item); ?>
            <?php endif; ?>
	        <?php if($item->hasAddons()): ?>
		        <?php foreach($item->getAddons() as $addon): ?>
			        <?php table_row($addon); ?>
		        <?php endforeach; ?>
	        <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<?php

function table_row($item) {

	?>
	<tr class="<?php if($item->isAddon()): ?>addon<?php else: ?>product<?php endif; ?>">
		<td class="name">
			<?php if($item->isAddon()): ?>
				<span class="indent">&nbsp;</span>
			<?php endif; ?>
			<?php if(LINK_PRODUCTS): ?>
				<a href="<?php echo site_url("/zibbra/product/".ZProduct::generateSlug($item->getProductid(),$item->getDescription())."/"); ?>" class="zibbra-cart-product"><?php echo $item->getDescription(); ?></a>
			<?php else: ?>
				<span class="zibbra-cart-product"><?php echo $item->getDescription(); ?></span>
			<?php endif; ?>
		</td>
		<td class="center quantity">
			<input type="number" min="1" step="1" name="quantity[<?php echo $item->getCartitemid(); ?>]" size="3" maxlength="3" value="<?php echo $item->getQuantity(); ?>" size="2"<?php if(LOCK_QUANTITY): ?> class="readonly" readonly<?php endif; ?> />
		</td>
		<?php if(!SHOW_INCL_VAT): ?>
			<td class="right price">
				<?php if($item->hasDiscount()): ?>
					<?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getAmount()-$item->getDiscount(),2,",",""); ?>
					<span class="discount">&nbsp;(<s><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getAmount(),2,",",""); ?></s>)</span>
				<?php else: ?>
					<?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getTotal(),2,",",""); ?>
				<?php endif; ?>
			</td>
			<td class="right total"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getTotal(),2,",",""); ?></td>
			<td class="right vat"><?php echo $item->getVat()*100; ?>%</td>
		<?php else: ?>
			<?php
			$amount = ($item->getAmount() - $item->getDiscount()) * (1 + $item->getVat());
			$nodiscount = $item->getAmount() * (1 + $item->getVat());
			$total = $amount * $item->getQuantity();
			?>
			<td class="right price">
				<?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($amount,2,",",""); ?>
				<?php if($item->hasDiscount()): ?>
					<span class="discount">&nbsp;(<s><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($nodiscount,2,",",""); ?></s>)</span>
				<?php endif; ?>
			</td>
			<td class="right total"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($total,2,",",""); ?></td>
		<?php endif; ?>
		<td class="actions">
			<?php if(!LOCK_ADDONS || (LOCK_ADDONS && !$item->isAddon())): ?>
				<a class="zibbra-button" href="#<?php echo $item->getCartitemid(); ?>" title="<?php echo __("Remove item", Zibbra_Plugin::LC_DOMAIN); ?>" confirm="<?php echo __("Are you sure you want to remove this item from your cart?", Zibbra_Plugin::LC_DOMAIN); ?>">
					<div class="icon icon-remove glyphicon glyphicon-trash"></div>
				</a>
			<?php endif; ?>
		</td>
	</tr>
	<?php

} // end function

?>