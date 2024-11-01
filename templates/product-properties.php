<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

define("ZIBBRA_SPLIT_PROPERTIES", get_option("zibbra_product_split_properties","N")=="Y");

?>
<div id="zibbra-product-properties">
	<h3 class="collapse-header">
		<a href="#collapse-properties" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-properties">
			<span class="title"><?php echo __("Product Specifications"); ?></span>
			<span class="icon icon-arrow-up"></span>
		</a>
	</h3>
	<div id="collapse-properties" class="collapse in">
		<?php $last_group = null; ?>
		<div class="column<?php if(!ZIBBRA_SPLIT_PROPERTIES || !$split_properties): ?> full-width<?php endif; ?>">
			<table class="table">
				<?php foreach($product->getProperties() as $index=>$property): ?>
					<?php $group = $property->getGroup(); ?>
					<?php if(ZIBBRA_SPLIT_PROPERTIES && $split_properties!==false && $index==$split_properties): ?>
							</table>
						</div>
						<div class="column">
							<table class="table">
					<?php endif; ?>
					<?php if(($last_group==null || ($last_group!=null && $last_group!=$group)) && !empty($group)): ?>
						<tr>
							<td colspan="2"><h4><?php echo $group; ?></h4></td>
						</tr>
					<?php endif;?>
						<tr>
							<td valign="top" class="zibbra-product-property-name"><?php echo $property->getName(); ?>:</td>
							<td valign="top" class="zibbra-product-property-value"><?php echo $property->getValue(); ?><?php if($property->hasUnit()): ?>&nbsp;<?php echo $property->getUnit(); ?><?php endif; ?></td>
						</tr>
					<?php $last_group = $group; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>