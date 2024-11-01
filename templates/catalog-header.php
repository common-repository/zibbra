<?php
/**
 * Catalog page
 *
 * @package Zibbra
 */

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$category = z_get_category();
$manufacturer = z_get_manufacturer();

?>
<div class="zibbra-catalog-header">

	<?php if(!empty($category)): ?>
	
		<?php if(!HEADER_SIDEBAR && $category->hasImages()): ?>
		
			<img src="<?php echo $category->getFirstImage(); ?>" border="0" align="right" />
			
		<?php endif; ?>
		
		<h2 class="name"><?php echo $category->getName(); ?></h2>
		
		<?php if(!HEADER_SIDEBAR && $category->hasDescription()): ?>
		
			<p class="description"><?php echo nl2br($category->getDescription()); ?></p>
			
		<?php endif; ?>
		
	<?php elseif(!empty($manufacturer)): ?>
	
		<?php if(!HEADER_SIDEBAR && $manufacturer->hasImage()): ?>
		
			<img src="<?php echo $manufacturer->getImage(); ?>" border="0" align="right" />
			
		<?php endif; ?>
		
		<h2 class="name"><?php echo $manufacturer->getName(); ?></h2>
	
	<?php endif; ?>
	
</div>