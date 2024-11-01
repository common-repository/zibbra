<?php

class Zibbra_Plugin_Module_Catalog extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "catalog";

	const QUERY_VAR_SLUG = "slug";
	const QUERY_VAR_PAGE = "page";
	const QUERY_VAR_MANUFACTURER = "manufacturer";
	const QUERY_VAR_IN_STOCK = "is";
	const QUERY_VAR_LIMIT = "lm";
	const QUERY_VAR_SORT_TYPE = "st";
	const QUERY_VAR_SORT_DIR = "sd";
	const QUERY_VAR_AJAX = "ajax";
	const QUERY_VAR_PRICE = "pi";
	const QUERY_VAR_PROPERTIES = "po";
	const QUERY_VAR_MANUFACTURERS = "mf";

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var ZCategory
	 */
	private $category;

	public function getPageTitle() {

		if(empty($this->title)) {

			$this->title = __("Catalog", Zibbra_Plugin::LC_DOMAIN);

		} // end if

		return $this->title;

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR_SLUG,
			self::QUERY_VAR_PAGE,
			self::QUERY_VAR_MANUFACTURER,
			self::QUERY_VAR_IN_STOCK,
			self::QUERY_VAR_LIMIT,
			self::QUERY_VAR_SORT_TYPE,
			self::QUERY_VAR_SORT_DIR,
			self::QUERY_VAR_AJAX,
			self::QUERY_VAR_PRICE,
			self::QUERY_VAR_PROPERTIES,
			self::QUERY_VAR_MANUFACTURERS
		];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/catalog/manufacturer/(.+?)/page/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=all&'.self::QUERY_VAR_MANUFACTURER.'=$matches[1]&'.self::QUERY_VAR_PAGE.'=$matches[2]',
			'zibbra/catalog/manufacturer/(.+?)/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=all&'.self::QUERY_VAR_MANUFACTURER.'=$matches[1]',
			'zibbra/catalog/(.+?)/manufacturer/(.+?)/page/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=$matches[1]&'.self::QUERY_VAR_MANUFACTURER.'=$matches[2]&'.self::QUERY_VAR_PAGE.'=$matches[3]',
			'zibbra/catalog/(.+?)/manufacturer/(.+?)/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=$matches[1]&'.self::QUERY_VAR_MANUFACTURER.'=$matches[2]',
			'zibbra/catalog/(.+?)/page/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=$matches[1]&'.self::QUERY_VAR_PAGE.'=$matches[2]',
			'zibbra/catalog/page/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=all&'.self::QUERY_VAR_PAGE.'=$matches[1]',
			'zibbra/catalog/(.+?)/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=$matches[1]',
			'zibbra/catalog/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_SLUG.'=all'
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		if(($slug = $wp_query->get(self::QUERY_VAR_SLUG))!=="") {

			// Load stylesheet and javascript

			wp_enqueue_style("wp-plugin-zibbra-catalog", plugins_url("css/catalog.css",ZIBBRA_BASE_DIR."/css"));
			wp_enqueue_script("wp-plugin-zibbra-nouislider", plugins_url("jscripts/nouislider.min.js",ZIBBRA_BASE_DIR."/jscripts"));
			wp_enqueue_script("wp-plugin-zibbra-catalog", plugins_url("jscripts/catalog.js",ZIBBRA_BASE_DIR."/jscripts"));
			wp_enqueue_script("wp-plugin-zibbra-catalog-filters", plugins_url("jscripts/catalog.filters.js",ZIBBRA_BASE_DIR."/jscripts"));

			// Prepare the query

			$z_query->init();

			// Pagination

			$z_query->set("items_per_page", $wp_query->get(self::QUERY_VAR_LIMIT)!=="" ? $wp_query->get(self::QUERY_VAR_LIMIT) : get_option("zibbra_catalog_products_per_page",10));
			$z_query->set("paged", (int) $wp_query->get(self::QUERY_VAR_PAGE) ?: 1);
			$z_query->set("limit_options", $this->get_limit_options());

			// Sorting

			$orderby = new stdClass();
			$orderby->type = $wp_query->get(self::QUERY_VAR_SORT_TYPE)!=="" ? $wp_query->get(self::QUERY_VAR_SORT_TYPE) : get_option("zibbra_catalog_default_sort_type", "times_sold");
			$orderby->dir = $wp_query->get(self::QUERY_VAR_SORT_DIR)!=="" ? $wp_query->get(self::QUERY_VAR_SORT_DIR) : get_option("zibbra_catalog_default_sort_dir", "desc");

			$z_query->set("orderby", $orderby->type);
			$z_query->set("order", $orderby->dir);
			$z_query->set("sort_options", $this->get_sort_options($orderby));

			// Category filter

			$category = null;

			if($slug!=="all") {

				// Try to load the category

				if(($category = ZCategory::getCategoryBySlug($slug,true))===false) {

					return false;

				} // end if

				$this->title = $category->getName();

				$z_query->set("category", $category);
				$z_query->set("category_id", $category->getCategoryid());
				$z_query->set("slug", $slug);

			} // end if

			// Manufacturer filter(s)

			$manufacturers = $wp_query->get(self::QUERY_VAR_MANUFACTURER) ?: false;

			if($wp_query->get(self::QUERY_VAR_MANUFACTURERS)!=="") {

				$manufacturers = implode(",",$wp_query->get(self::QUERY_VAR_MANUFACTURERS));

			} // end if

			$z_query->set("manufacturer_id", $manufacturers);

			// Stock filter

			$z_query->set("in_stock", $wp_query->get(self::QUERY_VAR_IN_STOCK)=="Y");

			// Price filter

			$z_query->set("price", $wp_query->get(self::QUERY_VAR_PRICE) ?: false);

			// Properties filter

			$z_query->set("properties", $wp_query->get(self::QUERY_VAR_PROPERTIES) ?: false);

			// Check if this is an AJAX call

			$z_query->set("ajax", $wp_query->get(self::QUERY_VAR_AJAX)=="Y");

			// Execute the query and load the products

			$products = $z_query->get_products();

			// Register META info

			$this->category = $category;
			add_action("wp_head", array($this, "register_meta"));

			// Register Analytics code

			$this->registerAnalytics($products);

			// Return template name

			return self::MODULE_NAME;

		} // end if

		return false;

	} // end function
	
	public function register_meta() {
		
		echo "<meta property=\"og:url\" content=\"".get_catalog_link()."\" />\n";
		echo "<meta property=\"og:title\" content=\"".$this->title."\" />\n";
		echo "<meta property=\"og:description\" content=\"".$this->title."\" />\n";
		echo "<meta property=\"og:type\" content=\"product.group\" />\n";
		
		if($this->category!==null && $this->category->hasImages()) {

			echo "<meta property=\"og:image\" content=\"".$this->category->getFirstImage()->getPath()."\" />\n";
			
		} // end if		
		
	} // end function
	
	private function registerAnalytics($products) {

		/** @var Zibbra_Plugin_Ga $ga */
		
		if(($ga = Zibbra_Plugin_Controller::getInstance()->getGa())!==false) {

			/** @var ZProduct[] $products */

			foreach($products as $position=>$product) {
			
				$data = new Zibbra_Plugin_Ga_Data_Impression($product->getCode(), $product->getName());
	
				if($product->hasCategories() && $tree = $product->getCategoryTree()) {
				
					$data->setCategory(implode("/",$tree));
	
				} // end if
				
				if($product->hasManufacturer()) {
				
					$data->setBrand($product->getManufacturer()->getName());
	
				} // end if
				
				$data->setPosition($position+1);
				$data->setList("Catalog");
						
				$ga->registerData($data);
				
			} // end foreach
			
		} // end if
		
	} // end function
	
	private function get_sort_options($orderby) {
		
		$sort_options = array();
		
		$labels = array(
			"times_sold"=>__("Best buy", Zibbra_Plugin::LC_DOMAIN),
			"name"=>__("Name", Zibbra_Plugin::LC_DOMAIN),
			"price"=>__("Price", Zibbra_Plugin::LC_DOMAIN),
			"timestamp_insert"=>__("Newest", Zibbra_Plugin::LC_DOMAIN)
		);
				
		foreach($labels as $type=>$label) {
			
			$sort_option = new stdClass();
			$sort_option->type = $type;
			$sort_option->label = $label;
			$sort_option->active = false;
			$sort_option->dir = $orderby->dir;
			$sort_option->classes = array();
			
			if($orderby->type == $type) {

				$sort_option->active = true;
				$sort_option->classes[] = "active";
				
			} // end if
			
			if($orderby->dir == "asc") {

				$sort_option->classes[] = "asc";
				
			}else{

				$sort_option->classes[] = "desc";
				
			} // end if
			
			$sort_options[$type] = $sort_option;
					
		} // end foreach
		
		return $sort_options;
		
	} // end function
	
	private function get_limit_options() {
		
		$limit_options = array();

		$min_limit = 5;
		$max_limit = 50;
		$limit = $default_limit = (int) get_option("zibbra_catalog_products_per_page",10);
		
		$limit_options[] = $default_limit;

		while(($limit/2) > $min_limit) {
		
			$limit_options[] = $limit = $limit / 2;
		
		} // end while

		sort($limit_options);
		$limit = $default_limit;
		
		while(($limit + $limit_options[0]) < $max_limit) {
		
			$limit_options[] = $limit = $limit + $limit_options[0];
		
		} // end while
		
		return $limit_options;
		
	} // end function

} // end class