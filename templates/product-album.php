<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$images = $product->hasImages() ? $product->getImages() : false;

?>
<div id="zibbra-product-album" class="carousel slide<?php if($images && count($images)==1): ?> single<?php endif; ?>" data-ride="carousel" data-interval="false">
			
	<?php if($images && count($images)>1): ?>

		<!-- Indicators -->
		
		<ol class="carousel-indicators">
			<?php foreach($images as $index=>$image): ?>
				<li data-target="#zibbra-product-album" data-slide-to="<?php echo $index; ?>"<?php if($index==0): ?> class="active"<?php endif; ?>></li>
			<?php endforeach; ?>
		</ol>
	
	<?php endif; ?>

	<!-- Wrapper for slides -->
	
	<div class="carousel-inner" role="listbox">
	
		<?php foreach($images as $index=>$image): ?>
		
			<div class="item<?php if($index==0): ?> active<?php endif; ?>">
				<img src="<?php echo $image->getPath(); ?>" border="0" />
			</div>
			
		<?php endforeach; ?>
		
	</div>

	<?php if($images && count($images)>1): ?>
	
		<!-- Controls -->
		
		<a class="left carousel-control" href="#zibbra-product-album" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#zibbra-product-album" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	
	<?php endif; ?>
	
</div>
			
<?php if($images && count($images)>1): ?>

	<!-- Thumbnails -->
	
	<div class="thumbnails">
		<?php foreach($images as $index=>$image): ?>
			<div class="thumbnail-container">
				<div data-target="#zibbra-product-album" data-slide-to="<?php echo $index; ?>" class="thumbnail">
					<img src="<?php echo $image->getPath(42); ?>" border="0" />
				</div>
			</div>
		<?php endforeach; ?>
	</ol>

<?php endif; ?>

<div class="clearfix"></div>