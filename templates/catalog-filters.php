<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$filters = z_get_filters();
define("ZIBBRA_CATALOG_FILTERS_COLLAPSED", get_option("zibbra_catalog_filters_collapsed","N")=="Y");

?>
<?php foreach($filters as $filter): ?>
	<?php if($filter->getType()==ZProductFilter::TYPE_RANGE && $filter->hasMinMax() && $filter->getMin()!=$filter->getMax()): ?>
		<div id="<?php echo $filter->getField(); ?>" class="zibbra-filter <?php echo $filter->getType(); ?>">
			<h3 class="header"><span><?php echo $filter->getName(); ?></span></h3>
			<div class="range">
				<span class="slider-value-min">&euro; <?php echo floor(round($filter->getMin(),2)); ?></span>
				<span class="slider-value-max">&euro; <?php echo ceil($filter->getMax()); ?></span>
				<div id="<?php echo $filter->getField(); ?>_range" class="slider noUi-extended" data-prefix="&euro;" data-field="<?php echo $filter->getField(); ?>" data-min="<?php echo floor(round($filter->getMin(),2)); ?>" data-max="<?php echo ceil($filter->getMax()); ?>" data-value-min="<?php echo floor(round($filter->getMin(),2)); ?>" data-value-max="<?php echo ceil($filter->getMax()); ?>"></div>
			</div>
		</div>
	<?php endif; ?>
	<?php if($filter->getType()==ZProductFilter::TYPE_LIST && count($filter->getOptions())>0): ?>
		<div id="<?php echo $filter->getField(); ?>" class="zibbra-filter <?php echo $filter->getType(); ?>">
			<h3 class="header"><span><?php echo $filter->getName(); ?></span><div class="icon"></div></h3>
			<div id="<?php echo $filter->getField(); ?>_list" class="list">
				<?php foreach($filter->getOptions() as $index=>$option): ?>
					<input id="<?php if($filter->getField()!="manufacturer"): ?>property-<?php endif; ?><?php echo $filter->getField(); ?>-<?php echo $index; ?>" type="checkbox" name="<?php echo $filter->getField(); ?>" value="<?php echo $option['value']; ?>" />
					<label for="<?php if($filter->getField()!="manufacturer"): ?>property-<?php endif; ?><?php echo $filter->getField(); ?>-<?php echo $index; ?>"><?php echo $option['label']; ?>&nbsp;<?php if($filter->hasUnit()): ?><span class="unit"><?php echo $filter->getUnit(); ?></span><?php endif; ?><span class="suffix">(<span class="count"><?php echo $option['count']; ?></span>)</span></label>
				<?php endforeach; ?>
				<div class="clearfix"></div>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>
<?php if(ZIBBRA_SHOW_STOCK): ?>
	<div id="in_stock" class="zibbra-filter stock">
		<h3 class="header">
			<span><?php echo __("Stock", Zibbra_Plugin::LC_DOMAIN); ?></span>
		</h3>
		<div class="list">
			<input id="filter_in_stock" type="checkbox" name="in_stock" value="Y" />
			<label for="filter_in_stock"><?php echo __("In stock only", Zibbra_Plugin::LC_DOMAIN); ?></label>
			<div class="clearfix"></div>
		</div>
	</div>
<?php endif; ?>
<?php if(ZIBBRA_CATALOG_FILTERS_COLLAPSED): ?>
<script> Zibbra.Catalog.FILTERS_COLLAPSED = true; </script>
<?php endif; ?>