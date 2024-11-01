<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$product = the_product();

define("ZIBBRA_SHOW_STOCK", get_option("zibbra_catalog_show_stock","Y")=="Y");
define("ZIBBRA_SHOW_STOCK_BACKORDER", get_option("zibbra_catalog_show_stock_backorder","Y")=="Y");
define("ZIBBRA_SHOW_STOCK_QUANTITY", get_option("zibbra_catalog_show_stock_quantity","Y")=="Y");
define("ZIBBRA_TITLE_TOP", get_option("zibbra_product_title_top","N")=="Y");
define("ZIBBRA_QUANTITY_SELECTOR", get_option("zibbra_product_quantities", "N") == "Y");
define("ZIBBRA_ALLOW_BACKORDER", $product->allowBackorder());
define("ZIBBRA_IN_STOCK", $product->isInStock());

$class = array("row");
$class[] = ($product->hasImages() ? "album" : "no-album");
$class[] = ($product->hasProperties() ? "properties" : "no-properties");
$class[] = ($product->hasSuggestions() ? "suggestions" : "no-suggestions");
$class[] = (ZIBBRA_TITLE_TOP ? "title-top" : "title-info");

?>
<?php get_zibbra_header(); ?>

	<?php if(ZIBBRA_TITLE_TOP): ?>

		<div class="page-header zibbra-product-title">
			<h1><?php echo $product->getName(); ?></h1>	
		</div>
				
	<?php endif; ?>
		
	<form id="zibbra-product-form" name="zibbra-product-form" method="post">
	
		<div id="zibbra-product" itemscope itemtype="http://schema.org/Product" class="<?php echo implode(" ",$class); ?>">
	
			<?php if($product->hasImages()): ?>
			
				<section class="album<?php if(count($product->getImages())==1): ?> single<?php endif; ?>"><?php get_template_part("product", "album"); ?></section>
			
			<?php endif; ?>
			
			<section class="info"><?php get_template_part("product", "info"); ?></section>
			
			<?php if($product->hasSuggestions()): ?>
			
				<section class="suggestions"><?php get_template_part("product", "suggestions"); ?></section>
			
			<?php endif; ?>
			
			<div class="clearfix"></div>
			
			<?php if($product->hasLongDescription()): ?>
			
				<section class="description">
					<?php get_template_part("product", "description"); ?>
				</section>
			
			<?php endif; ?>
			
			<?php if($product->hasProperties()): ?>
			
				<section class="properties">
					<?php get_template_part("product", "properties"); ?>
				</section>
							
			<?php endif; ?>
			
			<div class="clearfix"></div>
		
		</div>
		
		<?php get_template_part("product", "form"); ?>
		
	</form>
		
	<?php get_template_part("product", "jscript"); ?>

<?php get_zibbra_footer(); ?>