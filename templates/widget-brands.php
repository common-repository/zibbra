<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<?php if(!empty($title)): ?>
	<?php echo $args['before_title']."<a href=\"".site_url("/zibbra/brands/")."\">".$title."</a>".$args['after_title']; ?>
<?php endif; ?>
<ul>
	<?php foreach($manufacturers as $manufacturer): ?>
		<?php
		
		$link = site_url("/zibbra/catalog/manufacturer/".$manufacturer->getManufacturerid()."/");
		
		?>
		<li>
			<?php if($manufacturer->hasImage()): ?>
				<a href="<?php echo $link; ?>" title="<?php echo $manufacturer->getName(); ?>"><img src="<?php echo $manufacturer->getImage()->getPath(THUMBNAIL_SIZE); ?>" width="<?php echo THUMBNAIL_SIZE; ?>" height="<?php echo THUMBNAIL_SIZE; ?>" border="0" alt="<?php echo $manufacturer->getName(); ?>" /></a>
			<?php else: ?>
				<a href="<?php echo $link; ?>" title="<?php echo $manufacturer->getName(); ?>"><img src="<?php echo plugins_url("images/no-image.png",ZIBBRA_BASE_DIR."/images"); ?>" width="<?php echo THUMBNAIL_SIZE; ?>" height="<?php echo THUMBNAIL_SIZE; ?>" border="0" alt="<?php echo $manufacturer->getName(); ?>" /></a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
<div class="clear"></div>
<?php echo $args['after_widget']; ?>