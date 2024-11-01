<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

define("SHOWALL",isset($template) && in_array("base-account-orders.php",$template->templates));

$orders = z_get_orders();

?>
<div class="page-header">
	<h1>
		<span><?php echo SHOWALL ? __("All your orders", Zibbra_Plugin::LC_DOMAIN) : __("Recent orders", Zibbra_Plugin::LC_DOMAIN); ?></span>
		<?php if(!SHOWALL && count($orders) > 0): ?>
			<a href="<?php echo site_url("/zibbra/account/orders/"); ?>"><?php echo __("View all", Zibbra_Plugin::LC_DOMAIN); ?></a>
		<?php endif; ?>
	</h1>
</div>
<?php if(SHOWALL): ?>
<p class="back">
	<a href="<?php echo site_url("/zibbra/account"); ?>"><?php echo __("Back to My Account", Zibbra_Plugin::LC_DOMAIN); ?></a>
</p>
<?php endif; ?>
<?php if(count($orders) > 0): ?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="zibbra-account">
		<thead>
			<tr>
				<th><?php echo __("Number", Zibbra_Plugin::LC_DOMAIN); ?></th>
				<th style="text-align:center;"><?php echo __("Date", Zibbra_Plugin::LC_DOMAIN); ?></th>
				<th style="text-align:right;"><?php echo __("Total", Zibbra_Plugin::LC_DOMAIN); ?></th>
				<th style="text-align:center;"><?php echo __("Status", Zibbra_Plugin::LC_DOMAIN); ?></th>
				<th style="text-align:center;"><?php echo __("Actions", Zibbra_Plugin::LC_DOMAIN); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($orders as $obj): ?>
				<tr>
					<td><?php echo $obj->getNumber(); ?></td>
					<td style="text-align:center;"><?php echo $obj->getDate()->format("d-m-Y"); ?></td>
					<td style="text-align:right;">&euro;&nbsp;<?php echo number_format($obj->getAmount(),2,",","."); ?></td>
					<td style="text-align:center;"><?php echo $obj->getStatus(); ?></td>
					<td style="text-align:center;"><a href="<?php echo site_url("/zibbra/account/order/".$obj->getNumber()."/"); ?>" target="_blank" title="<?php echo __("Download PDF", Zibbra_Plugin::LC_DOMAIN); ?>"><span class="icon icon-print"></span><span class="description"><?php echo __("View", Zibbra_Plugin::LC_DOMAIN); ?></span></a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p><?php echo __("No orders", Zibbra_Plugin::LC_DOMAIN); ?></p>
<?php endif; ?>