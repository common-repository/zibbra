<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php if($product->hasVariations()): ?>
	<?php	
		$arrVariations = $product->getVariations();
	?>
	<div class="zibbra-product-variations">
		<?php foreach($arrVariations as $oVariation): ?>
			<div class="zibbra-product-variation">
				<label for="variations_<?php echo $oVariation->getID(); ?>"><?php echo $oVariation->getName(); ?></label>
				<select id="variations_<?php echo $oVariation->getID(); ?>" name="variations[<?php echo $oVariation->getID(); ?>]" placeholder="<?php echo __("Make a choice"); ?>">
					<option value="-1">&nbsp;</option>
					<?php foreach($oVariation->getOptions() as $option): ?>
						<?php

						$label = $option->name;
						
						if(ZIBBRA_SHOW_STOCK && !$option->in_stock && ZIBBRA_SHOW_STOCK_BACKORDER) {

							$label .= " (".__("Not in stock", Zibbra_Plugin::LC_DOMAIN).")";
						
						} // end if				
						
						?>
						<option value="<?php echo $option->id; ?>"<?php if(!ZIBBRA_SHOW_STOCK && !ZIBBRA_ALLOW_BACKORDER && !$option->in_stock): ?> disabled="disabled"<?php endif; ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endforeach; ?>
		<div class="clearfix"></div>
	</div>
<?php endif; ?>
