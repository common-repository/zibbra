<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$category = z_get_category();

?>
<div id="zibbra-categories" class="zibbra-catalog-categories">
	<?php foreach($category->getChildren() as $subcategory): ?>
		<?php
		
			$link = site_url("/zibbra/catalog/".$subcategory->getSlug());
			
		?>
		<div class="zibbra-category-container">
			<div class="zibbra-category">
				<h3><a href="<?php echo $link; ?>"><?php echo $subcategory->getName(); ?></a></h3>
				<?php if($subcategory->hasImages()): ?>
					<div class="zibbra-category-image">
						<a href="<?php echo $link; ?>"><img src="<?php echo $subcategory->getFirstImage(); ?>" border="0" class="img-responsive" /></a>
					</div>
				<?php endif; ?>
				<a class="btn btn-primary" href="<?php echo $link; ?>"><?php echo __("View products",Zibbra_Plugin::LC_DOMAIN); ?></a>
			</div>
		</div>
		<?php endforeach; ?>
	<div class="clearfix"></div>
</div>