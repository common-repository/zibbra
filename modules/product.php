<?php

class Zibbra_Plugin_Module_Product extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "product";

	const QUERY_VAR_SLUG = "slug";
	const QUERY_VAR_ID = "id";

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var ZProduct
	 */
	private $product;

	public function getPageTitle() {

		return $this->title;

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR_ID,
			self::QUERY_VAR_SLUG
		];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/product/([0-9]+)-(.+?)/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_ID.'=$matches[1]&'.self::QUERY_VAR_SLUG.'=$matches[2]'
		];

	} // end function

	public function doAjax() {

		add_action("wp_ajax_zibbra_product_variations", array($this, "doUpdate"));
		add_action("wp_ajax_nopriv_zibbra_product_variations", array($this, "doUpdate"));

	} // end function

	public function doPost() {

		if(wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "add_product")) {

			$productid = (int) $_POST['id'];
			$quantity = (isset($_POST['quantity']) && is_numeric($_POST['quantity']) && (int) $_POST['quantity'] > 0 ? (int) $_POST['quantity'] : 1);

			// Try to load the product

			if(($product = ZProduct::getProduct($productid))===false) {

				return false;

			} // end if

			// Check if this product may be sold

			$allow_backorder = $product->allowBackorder();
			$in_stock = $product->isInStock();

			if(!$in_stock && !$allow_backorder) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to add this product to the shopping cart", Zibbra_Plugin::LC_DOMAIN));

				wp_redirect(site_url("/zibbra/product/".$product->getSlug()."/"));
				exit;

			} // end if

			// Check for required variations, if missing, redirect to detail page

			if($product->hasVariations() && !isset($_POST['variations'])) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Please choose one of the options", Zibbra_Plugin::LC_DOMAIN));

				wp_redirect(site_url("/zibbra/product/".$product->getSlug()."/"));
				exit;

			} // end if

			// Load the cart

			$cart = ZCart::getInstance();

			// Create an item

			$item = new ZCartItem();
			$item->setProductid($productid);
			$item->setQuantity($quantity);

			if(isset($_POST['variations'])) {

				$item->setVariations($_POST['variations']);

			} // end if

            if(isset($_POST['addons'])) {

			    foreach($_POST['addons'] as $addonid=>$quantity) {

			        $addon = new ZCartItem();
			        $addon->setProductid($addonid);
			        $addon->setParentid($productid);
			        $addon->setQuantity($quantity);
			        $item->addAddon($addon);

                } // end foreach

            } // end if

			// Add the item to the cart

			$cart->addItem($item);

			// Save the cart

			$cart->save();

			// Redirect to the cart

			wp_redirect(site_url("/zibbra/cart/"));
			exit;

		} // end if

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		if(($slug = $wp_query->get(self::QUERY_VAR_SLUG))!=="") {

			// Get query parameters

			$productid = (int) $wp_query->get(self::QUERY_VAR_ID);

			// Load stylesheet and javascript

			wp_enqueue_style("wp-plugin-zibbra-product", plugins_url("css/product.css",ZIBBRA_BASE_DIR."/css"));
			wp_enqueue_script("wp-plugin-zibbra-jquery-zoom", plugins_url("jscripts/jquery.zoom.min.js",ZIBBRA_BASE_DIR."/jscripts"));
			wp_enqueue_script("wp-plugin-zibbra-album", plugins_url("jscripts/album.js",ZIBBRA_BASE_DIR."/jscripts"));
			wp_enqueue_script("wp-plugin-zibbra-fakeselect", plugins_url("jscripts/fakeselect.js",ZIBBRA_BASE_DIR."/jscripts"));
			wp_enqueue_script("wp-plugin-zibbra-product", plugins_url("jscripts/product.js",ZIBBRA_BASE_DIR."/jscripts"));

			// Prepare the query

			$z_query->init();
			$z_query->set("product_id", $productid);

			// Execute the query and load the product

			/** @var ZProduct $product */

			$product = $z_query->get_product();

			// Register META info

			$this->product = $product;
			add_action("wp_head", array($this, "register_meta"));

			// Register Analytics code

			$this->registerAnalytics($product);

			// Calculate column-split for properties

			$this->calcPropertyColumns($product, $z_query);

			// Set the title

			$this->title = $product->getName();

			// Return template name

			return self::MODULE_NAME;

		} // end if

		return false;

	} // end function

	/**
	 * @todo Finish this code with multiple variation combinations. Also look at the JS file product.js:_onChangeVariation
	 */
	public function doUpdate() {
		
		$oProduct = ZProduct::getProduct((int) $_POST['id']);
		$response = $oProduct->getVariationCombinations(array_values($_POST['variations']));
		
		header("Content-Type: application/json");
		echo json_encode($response);
		exit;
		
	} // end function
	
	/**
	 * @todo Make the valuta dynamic
	 */
	public function register_meta() {
		
		// Get general product info
		
		$name = $this->product->getName();		
		$slug = $this->product->getSlug();
		$url = site_url("/zibbra/product/".$slug);
		$price = round($this->product->getPrice(),2);
		
		// Get description
		
		$description = $name;
		
		if($this->product->hasMetaDescription()) {

			$description = $this->product->getMetaDescription();
			
		}elseif($this->product->hasShortDescription()) {

			$description = $this->product->getShortDescription();
			
		} // end if
		
		// Get keywords
		
		$keywords = $name;
		
		if($this->product->hasMetaKeywords()) {

			$keywords = $this->product->getMetaKeywords();
			
		} // end if
		
		// Get primary image
		
		$image = false;
		
		if($this->product->hasImages()) {
			
			$image = $this->product->getFirstImage()->getPath();			
			
		} // end if
		
		// General meta tags

		echo "<meta name=\"description\" content=\"".substr($description,0,155)."\" />\n";
		echo "<meta name=\"keywords\" content=\"".$keywords."\" />\n";
		
		// Schema.org markup for Google+
		
		echo "<meta itemprop=\"name\" content=\"".$name."\" />\n";
		echo "<meta itemprop=\"description\" content=\"".$description."\" />\n";
		
		if($image) {
		
			echo "<meta itemprop=\"image\" content=\"".$image."\" />\n";
		
		} // end if
		
		// Twitter Card data
		
		echo "<meta name=\"twitter:title\" content=\"".$name."\" />\n";
		echo "<meta name=\"twitter:description\" content=\"".substr($description,0,200)."\" />\n";
		echo "<meta name=\"twitter:image:src\" content=\"".$image."\" />\n";
		
		// Open Graph data
		
		echo "<meta property=\"og:title\" content=\"".$name."\" />\n";
		echo "<meta property=\"og:type\" content=\"product\" />\n";
		echo "<meta property=\"og:url\" content=\"".$url."/\" />\n";
		echo "<meta property=\"og:description\" content=\"".$description."\" />\n";
		
		if($image) {

			echo "<meta property=\"og:image\" content=\"".$image."\" />\n";
			
		} // end if
		
		echo "<meta property=\"product:price:amount\" content=\"".$price."\" />\n";
		echo "<meta property=\"product:price:currency\" content=\"EUR\" />\n";
		
	} // end function
	
	private function registerAnalytics(ZProduct $product) {

		/** @var Zibbra_Plugin_Ga $ga */
		
		if(($ga = Zibbra_Plugin_Controller::getInstance()->getGa())!==false) {
			
			$data = new Zibbra_Plugin_Ga_Data_Product($product->getCode(), $product->getName());

			if($product->hasCategories() && $tree = $product->getCategoryTree()) {
			
				$data->setCategory(implode("/",$tree));

			} // end if
			
			if($product->hasManufacturer()) {
			
				$data->setBrand($product->getManufacturer()->getName());

			} // end if
			
			$data->setPrice(number_format($product->getPrice(),2,".",""));	
					
			$ga->registerData($data);
			
			// Register impressions for suggestions
			
			if($product->hasSuggestions()) {

				foreach($product->getSuggestions() as $position=>$suggestion) {
						
					$data = new Zibbra_Plugin_Ga_Data_Impression($suggestion->getCode(), $suggestion->getName());				
					$data->setPosition($position+1);
					$data->setList("Suggestions");
				
					$ga->registerData($data);
				
				} // end foreach				
				
			} // end if
			
		} // end if
		
	} // end function
	
	private function calcPropertyColumns(ZProduct $product, Zibbra_Plugin_Query $z_query) {
		
		if(get_option("zibbra_product_split_properties","N")=="Y" && $product->hasProperties()) {
			
			$properties = $product->getProperties();
			$middle = floor(count($properties) / 2);
			$groups = array();
			$middlegroup = null;
			$lastgroup = null;
			$groupindex = 0;
			
			foreach($properties as $index=>$property) {
				
				if($lastgroup!=null && $lastgroup!=$property->getGroup()) $groupindex++;
				
				if(!isset($groups[$groupindex])) {
					
					$groups[$groupindex] = 1;
					
				}else{
					
					$groups[$groupindex]++;
					
				} // end if
				
				$lastgroup = $property->getGroup();
				
				if($index==$middle) $middlegroup = $groupindex;
				
			} // end foreach
			
			$right = $groups;
			$left = array_splice($right,0,$middlegroup);
			
			$left_total = array_sum($left);
			$right_total = array_sum($right);
			
			$left = $middle - $left_total;
			$right = $right_total - $middle;
			
			$split_properties = $left_total + $groups[$middlegroup];
			
			if($left>$right) $split_properties = $left_total + $groups[$middlegroup+1];
			
			if(count($properties)==$split_properties) $split_properties = false;
			
			$z_query->set("split_properties", $split_properties);
			
		} // end if
		
	} // end function

} // end class