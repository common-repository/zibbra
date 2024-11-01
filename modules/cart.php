<?php

class Zibbra_Plugin_Module_Cart extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "cart";

	public function getPageTitle() {

		return __("Your shopping cart", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/cart/?$' => 'index.php?zibbra='.self::MODULE_NAME
		];

	} // end function

	public function doAjax() {

		add_action("wp_ajax_zibbra_cart_update", array($this, "doUpdate"));
		add_action("wp_ajax_nopriv_zibbra_cart_update", array($this, "doUpdate"));

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		// Check if the reset toggle is passed

		if(isset($_GET['reset'])) {

			ZCart::reset();
			wp_redirect(site_url("/zibbra/cart/"));
			exit;

		} // end if

		// Load stylesheet and javascript

		wp_enqueue_style("wp-plugin-zibbra-cart", plugins_url("css/cart.css",ZIBBRA_BASE_DIR."/css"));
		wp_enqueue_script("wp-plugin-zibbra-cart", plugins_url("jscripts/cart.js",ZIBBRA_BASE_DIR."/jscripts"));

		// Load the cart

		if(($cart = ZCart::getInstance())===false) {

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to load the shopping cart", Zibbra_Plugin::LC_DOMAIN));

			wp_redirect(site_url("/"));
			exit;

		} // end if

		// Assign the cart to the query

		$z_query->init();
		$z_query->set("cart", $cart);
		$z_query->set("site_url", site_url());

		// Return template name

		return self::MODULE_NAME;

	} // end function
	
	public function doUpdate() {
		
		$response = false;
		
		if(isset($_POST['update']) && isset($_POST['quantity'])) {

			$itemid = intval($_POST['update']);
			$quantity = intval($_POST['quantity']);

			if($quantity > 0) {

				$cart = ZCart::getInstance();

				$item = $cart->getItem($itemid);

				if($item instanceof ZCartItem) {

					if($item->getQuantity() !== $quantity) {

						$item->setQuantity($quantity);
						$item->save();

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Quantity has been updated", Zibbra_Plugin::LC_DOMAIN));

					} // end if

					$response = true;

				} // end if

			}else{

				$_POST['delete'] = $itemid;

			} // end if
			
		} // end if
		
		if(isset($_POST['delete'])) {
			
			$cart = ZCart::getInstance();
			
			$item = $cart->getItem(intval($_POST['delete']));

			if($item instanceof ZCartItem) {

				$item->delete();

				if($item->hasAddons()) {

					foreach($item->getAddons() as $addon) {

						$addon->delete();

					} // end foreach

				} // end if

				$response = true;

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Item deleted from cart", Zibbra_Plugin::LC_DOMAIN));

			} // end if
			
		} // end if
		
		header("Content-Type: application/json");
		echo json_encode($response);
		exit;
		
	} // end function

} // end class