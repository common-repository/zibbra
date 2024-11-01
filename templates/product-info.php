<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php 
define("ZIBBRA_TITLE_TOP", get_option("zibbra_product_title_top","N")=="Y");
?>
<div class="zibbra-product-info">
	<h1 itemprop="name" class="zibbra-product-title"><?php echo $product->getName(); ?></h1>
	<p class="zibbra-product-sku">
		<label><?php echo __("SKU", Zibbra_Plugin::LC_DOMAIN); ?>:</label><span><?php echo $product->getCode(); ?></span>
	</p>
	<?php if($product->hasManufacturer()): ?>
		<?php $manufacturer = $product->getManufacturer(); ?>
		<p class="zibbra-product-manufacturer" itemprop="brand" itemscope itemtype="http://schema.org/Brand">
			<label><?php echo __("Brand", Zibbra_Plugin::LC_DOMAIN); ?>:</label><a href="<?php echo site_url("/zibbra/catalog/manufacturer/".$manufacturer->getManufacturerid()."/"); ?>"><span itemprop="name"><?php echo $manufacturer; ?></span></a>
		</p>
	<?php endif; ?>
	<?php if(ZIBBRA_SHOW_STOCK && (ZIBBRA_SHOW_STOCK_BACKORDER || ZIBBRA_IN_STOCK)): ?>
	<p class="zibbra-product-stock">
		<?php if(ZIBBRA_SHOW_STOCK_QUANTITY): ?>
			<label><?php echo __("Stock", Zibbra_Plugin::LC_DOMAIN); ?>:</label><span class="<?php echo $product->isInStock() ? "in_stock" : ""; ?>"><?php echo $product->getStock(); ?></span>
		<?php else: ?>
			<label><?php echo __("Availability", Zibbra_Plugin::LC_DOMAIN); ?>:</label><span class="<?php echo $product->isInStock() ? "in_stock" : ""; ?>"><?php echo $product->isInStock() ? __("In stock", Zibbra_Plugin::LC_DOMAIN) : __("Not in stock", Zibbra_Plugin::LC_DOMAIN); ?></span>
		<?php endif; ?>
	</p>
	<?php endif; ?>
	<div class="zibbra-product-description" itemprop="description">
		<p><?php echo $product->getShortDescription(); ?></p>
	</div>

	<?php get_template_part("product", "addons"); ?>
	<?php get_template_part("product", "variations"); ?>

	<div class="zibbra-product-actions">
	
		<?php get_template_part("product", "buy"); ?>
		<?php get_template_part("product", "social"); ?>
		
	</div>
	<div class="zibbra-product-categories">
		<span><?php echo __("Categories", Zibbra_Plugin::LC_DOMAIN); ?>:</span>
		<?php foreach($product->getSortedCategoryTrees() as $tree): ?>
			<ul>
				<?php foreach($tree as $category): ?>
					<?php $link = site_url("/zibbra/catalog/".$category->getSlug()."/"); ?>
					<li><a href="<?php echo $link; ?>"><?php echo $category->getName(); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
		<div class="clearfix"></div>
	</div>
</div>