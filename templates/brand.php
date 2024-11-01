<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$manufacturers = z_get_manufacturers();

?>

<?php get_zibbra_header(__("Our Brands", Zibbra_Plugin::LC_DOMAIN)); ?>

	<div id="zibbra-brands">
		<ul class="brands">
			<?php foreach($manufacturers as $manufacturer): ?>
				<?php $link = site_url("/zibbra/catalog/manufacturer/".$manufacturer->getManufacturerid()."/"); ?>
				<li>
					<div class="zibbra-brand">
						<div class="brand-image">
							<a href="<?php echo $link; ?>">
								<?php
								
								$uri = plugins_url("images/no-image.png",ZIBBRA_BASE_DIR."/images");
								
								if($manufacturer->hasImage()) {
		
									$uri = $manufacturer->getImage()->getPath(200);
		
								} // end if
								
								?>
								<img src="<?php echo $uri; ?>" alt="" title="<?php echo $manufacturer->getName(); ?>"<?php if(!$manufacturer->hasImage()): ?> class="noimage"<?php endif; ?>>
							</a>
						</div>
						<div class="brand-name">
							<a href="<?php echo $link; ?>"><?php echo $manufacturer->getName(); ?></a>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

<?php get_zibbra_footer(); ?>