<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<div>
	<?php wp_nonce_field("add_product",Zibbra_Plugin::FORM_ACTION); ?>
	<input type="hidden" name="id" value="<?php echo $product->getProductid(); ?>" />
</div>