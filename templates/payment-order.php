<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

define("VALUTA_SYMBOL", $order->getValutaSymbol());

?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="zibbra-order">
	<thead>
		<tr>
			<th><?php echo __("Product", Zibbra_Plugin::LC_DOMAIN); ?></th>
			<th class="right"><?php echo __("Total", Zibbra_Plugin::LC_DOMAIN); ?></th>
			<th class="right"><?php echo __("VAT", Zibbra_Plugin::LC_DOMAIN); ?></th>
		</tr>
	</thead>
	<tbody>			
		<?php foreach($order->getItems() as $item): ?>		
			<tr>				
				<td><?php echo (int) $item->getQuantity(); ?>&nbsp;x&nbsp;<?php echo $item->getDescription(); ?></td>
				<td class="right"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($item->getTotal(),2,",",""); ?></td>
				<td class="right"><?php echo $item->getVat()*100; ?>%</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<table cellpadding="0" cellspacing="4" border="0" class="zibbra-totals">
	<tbody>	
		<tr>						
			<td><?php echo __("Total VAT", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
			<td class="right" class="amount_vat"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($order->getVat(),2,",",""); ?></td>
		</tr>
		<tr>						
			<td><?php echo __("Total VAT incl.", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
			<td class="right" class="amount_incl"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($order->getAmountIncl(),2,",",""); ?></td>
		</tr>				
	</tbody>				
</table>

<div class="clear"></div>