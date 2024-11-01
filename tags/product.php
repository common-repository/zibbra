<?php

function have_products() {
	
	global $z_query;
	
	return $z_query->have_products();
	
} // end function

function get_product_id() {
	
	global $z_query;
		
	$product = $z_query->item;
	
	if($product instanceof ZProduct) {
		
		return $product->getProductid();
	
	} // end if
	
	return null;
	
} // end function

function product_id() {
	
	echo get_product_id();
	
} // end if

function the_product() {
	
	global $z_query;
		
	$product = $z_query->the_item();
	
	if($product instanceof ZProduct) {
		
		// Register the product variable as query vars, they will be extracted into the template engine
		
		$z_query->set("product", $product);
		
		return $product;
	
	} // end if
	
	return false;
	
} // end function

function zibbra_product_link() {

	$postid = get_the_ID();

	if(!empty($postid)) {

		$product_url = get_post_meta($postid, "_zibbra_product_url", true);

		if(!empty($product_url)) {
				
			echo "<a href=\"".$product_url."\">View product</a>";
				
		} // end if

	} // end if

} // end function

function get_zibbra_product_url() {

	$postid = get_the_ID();

	if(!empty($postid)) {

		return get_post_meta($postid, "_zibbra_product_url", true);

	} // end if
	
	return null;

} // end function

function get_zibbra_add_product_nonce() {

	return wp_create_nonce("add_product", Zibbra_Plugin::FORM_ACTION);

} // end function

?>