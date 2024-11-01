<?php
/**
 * Catalog page
 *
 * @package Zibbra
 */

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

define("SHOW_FILTERS", get_option("zibbra_catalog_show_filters","Y")=="Y");
define("HEADER_SIDEBAR", get_option("zibbra_catalog_header_sidebar","N")=="Y");
define("TOOLBAR_SIDEBAR", get_option("zibbra_catalog_toolbar_sidebar","N")=="Y");
define("LAYOUT_GRID_LIST", get_option("zibbra_catalog_grid_list", "grid"));
define("ZIBBRA_IMAGE_INDEX",0);
define("ZIBBRA_SHOW_STOCK", get_option("zibbra_catalog_show_stock","Y")=="Y");
define("ZIBBRA_SHOW_STOCK_BACKORDER", get_option("zibbra_catalog_show_stock_backorder","Y")=="Y");
define("ZIBBRA_SHOW_STOCK_QUANTITY", get_option("zibbra_catalog_show_stock_quantity","Y")=="Y");
define("THUMBNAIL_SIZE", get_option("zibbra_catalog_category_thumbnail_size","120"));
define("BUTTON_ADD_TO_CART", get_option("zibbra_catalog_redirect_addtocart", "addtocart")=="addtocart");

$category = z_get_category();
$manufacturer = z_get_manufacturer();

$class = array("row");
$class[] = LAYOUT_GRID_LIST;
$class[] = (SHOW_FILTERS ? "filters" : "no-filters");
$class[] = (HEADER_SIDEBAR ? "header-sidebar" : "header-normal");
$class[] = (TOOLBAR_SIDEBAR ? "toolbar-sidebar" : "toolbar-normal");

?>
<?php get_zibbra_header(); ?>
			
	<div id="zibbra-catalog" class="<?php echo implode(" ",$class); ?>">
		
		<?php if(!empty($category) && $category->hasChildren()): ?>
		
			<?php get_template_part("catalog", "header"); ?>
			
			<?php get_template_part("catalog", "categories"); ?>

		<?php elseif(have_products()): ?>
			
			<div class="zibbra-catalog-container">
				
				<?php if(!HEADER_SIDEBAR || !SHOW_FILTERS): ?>

					<?php get_template_part("catalog", "header"); ?>
				
				<?php endif; ?>
								
				<?php if(SHOW_FILTERS): ?>
			
					<div class="zibbra-catalog-sidebar">
					
						<?php if(HEADER_SIDEBAR): ?>
		
							<?php get_template_part("catalog", "header"); ?>
						
						<?php endif; ?>
					
						<?php if(TOOLBAR_SIDEBAR): ?>
											
							<?php get_template_part("catalog", "toolbar"); ?>
						
						<?php endif; ?>
				
						<div class="zibbra-catalog-filters">
					
							<?php get_template_part("catalog", "filters"); ?>
						
						</div>
					
					</div>
					
				<?php endif; ?>
					
				<div class="zibbra-catalog-products">
					
					<?php if(!TOOLBAR_SIDEBAR): ?>
										
						<?php get_template_part("catalog", "toolbar"); ?>
					
						<div class="clearfix"></div>
					
					<?php endif; ?>
					
					<?php while(have_products()) : the_product(); ?>
						
						<?php get_template_part("catalog", "product"); ?>
						
					<?php endwhile; ?>
						
					<?php get_template_part("catalog", "pagination"); ?>
						
				</div>
				
			</div>
		
		<?php endif; ?>
	
	</div>

<?php get_zibbra_footer(); ?>