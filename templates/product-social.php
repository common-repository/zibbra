<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$uri = $_SERVER['REQUEST_URI'];
$name = addslashes(stripslashes($product->getName()));
$description = addslashes(stripslashes(strip_tags($product->getShortDescription())));
$image = $product->hasImages() ? $product->getFirstImage()->getPath() : "";

?>
<div class="zibbra-product-social">
	<div class="zibbra-product-social-icon zibbra-product-social-facebook" title="Share on Facebook">
		<a href="javascript:zibbra.get('product').shareFacebook('<?php echo $uri; ?>', '<?php echo $name; ?>', '<?php echo $description; ?>', '<?php echo $image; ?>');">
			<span class="icon icon-facebook"></span>
		</a>
	</div>
	<div class="zibbra-product-social-icon zibbra-product-social-twitter" title="Share on Twitter">
		<a href="javascript:zibbra.get('product').shareTwitter('<?php echo $uri; ?>', '<?php echo $name; ?>', '<?php echo $description; ?>', '<?php echo $image; ?>');">
			<span class="icon icon-twitter"></span>
		</a>
	</div>
	<div class="zibbra-product-social-icon zibbra-product-social-google-plus" title="Share on Google+">
		<a href="javascript:zibbra.get('product').shareGooglePlus('<?php echo $uri; ?>', '<?php echo $name; ?>', '<?php echo $description; ?>', '<?php echo $image; ?>');">
			<span class="icon icon-googleplus"></span>
		</a>
	</div>
</div>