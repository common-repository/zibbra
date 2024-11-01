<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<script>
	Zibbra.Product.PRODUCTID = <?php echo $product->getProductid(); ?>;
	Zibbra.Product.SHOW_STOCK = <?php echo ZIBBRA_SHOW_STOCK ? "true" : "false"; ?>;
	Zibbra.Product.ALLOW_BACKORDER = <?php echo ZIBBRA_ALLOW_BACKORDER ? "true" : "false"; ?>;
	Zibbra.Product.registerProduct({
		"id": "<?php echo $product->getProductid(); ?>",
		"sku": "<?php echo $product->getCode(); ?>",
		"name": "<?php echo $product->getName(); ?>",
		"price": "<?php echo number_format($product->getPrice(),2,".",""); ?>"
	});
</script>