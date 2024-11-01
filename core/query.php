<?php

/**
 * Destroy the previous query and set up a new query
 * 
 * @uses $z_query
 */
function z_reset_query() {
	
	$GLOBALS['z_query'] = new Zibbra_Plugin_Query();
	
} // end function

function z_get_category() {
	
	global $z_query;
	
	return $z_query->get("category", null);
	
} // end function

function z_get_cart() {
	
	global $z_query;
	
	return $z_query->get("cart", null);
	
} // end function

function z_get_customer() {
	
	global $z_query;
	
	return $z_query->get("customer", null);
	
} // end function

function z_get_order() {
	
	global $z_query;
	
	return $z_query->get("order", null);
	
} // end function

function z_get_checkout() {
	
	global $z_query;
	
	return $z_query->get("checkout", null);
	
} // end function

function z_get_manufacturers() {

	global $z_query;
	
	return $z_query->get("manufacturers", array());	
	
} // end function

function z_get_orders() {
	
	global $z_query;
	
	return $z_query->get("orders", array());
	
} // end function

function z_get_invoices() {
	
	global $z_query;
	
	return $z_query->get("invoices", array());
	
} // end function

function z_get_manufacturer() {

	global $z_query;
	
	return $z_query->get("manufacturer", null);	
	
} // end function

function z_get_orderby() {
	
	global $z_query;
	
	$orderby = new StdClass();
	$orderby->type = $z_query->get("orderby");
	$orderby->dir = $z_query->get("order");
	
	return $orderby;
	
} // end function

function z_get_pagination() {
	
	global $z_query;
	
	$pagination = new StdClass();
	$pagination->total_rows = (int) $z_query->item_count;
	$pagination->limit = (int) $z_query->get("items_per_page");
	$pagination->pages = (int) $z_query->max_num_pages;
	$pagination->min_limit = (int) $z_query->min_limit;
	$pagination->max_limit = (int) $z_query->max_limit;
	$pagination->page = (int) $z_query->get("paged");
	$pagination->active = $pagination->total_rows > $pagination->min_limit;
	
	return $pagination;
	
} // end function

function z_get_filters() {

	return ZProduct::getFilters();	
	
} // end function

/**
 * The Zibbra Query class
 */
class Zibbra_Plugin_Query {

	/**
	 * Query vars set by the user
	 *
	 * @var array
	 */
	public $query;

	/**
	 * Query vars, after parsing
	 *
	 * @var array
	 */
	public $query_vars = array();
	
	/**
	 * List of items
	 * 
	 * @var array
	 */
	public $items;

	/**
	 * The current item
	 * 
	 * @var mixed
	 */
	public $item;
	
	/**
	 * The amount of items for the current query
	 * 
	 * @var int
	 */
	public $item_count = 0;
	
	/**
	 * Index of the current item in the loop
	 * 
	 * @var int
	 */
	public $current_item = -1;

	/**
	 * The amount of pages
	 *
	 * @var int
	 */
	public $max_num_pages = 0;

	/**
	 * Minimum limit for items per page
	 *
	 * @var int
	 */
	public $min_limit = 5;

	/**
	 * Maximum limit for items per page
	 *
	 * @var int
	 */
	public $max_limit = 50;
	
	/**
	 * Whether the loop has started and the caller is in the loop
	 * 
	 * @var boolean
	 */
	public $in_the_loop = false;
	
	/**
	 * Set if query is product
	 * 
	 * @var bool
	 */
	public $is_product = false;
	
	/**
	 * Set if query is category
	 * 
	 * @var bool
	 */
	public $is_category = false;
	
	/**
	 * Set if query is single item
	 * 
	 * @var bool
	 */
	public $is_single = false;

	/**
	 * Set if is single or is a product
	 *
	 * @var bool
	 */
	public $is_singular = false;

	/**
	 * Set if query couldn't find anything
	 * 
	 * @var bool
	 */
	public $is_404 = false;

	/**
	 * Set if query is paged
	 * 
	 * @var bool
	 */
	public $is_paged = false;
	
	/**
	 * Constructs a new query
	 */
	public function __construct($query="") {
		
		if(!empty($query)) {
			
			$this->query($query);
			
		} // end if
		
	} // end function

	/**
	 * Resets query flags to false
	 */
	private function init_query_flags() {
		
		$this->is_product = false;
		$this->is_category = false;
		$this->is_single = false;
		$this->is_404 = false;
		$this->is_paged = false;
		$this->is_singular = false;
		
	} // end function
	
	/**
	 * Initiates object properties and sets default values
	 */
	public function init() {
		
		unset($this->items);
		
		$this->item_count = 0;
		$this->current_item = -1;
		$this->max_num_pages = 0;
		$this->in_the_loop = false;
	
		$this->init_query_flags();
		
	} // end function

	/**
	 * Sets up the query by parsing query string
	 *
	 * @param string $query URL query string
	 * @return array List of items
	 */
	public function query($query) {
		
		$this->init();
		$this->query = $this->query_vars = wp_parse_args($query);
		
		return $this->get_items();
		
	} // end function

	/**
	 * Reparse the query vars
	 */
	public function parse_query_vars() {
		
		$this->parse_query();
		
	} // end function

	/**
	 * Fills in the query variables, which do not exist within the parameter
	 *
	 * @param array $array Defined query variables
	 * @return array Complete query variables with undefined ones filled in empty
	 */
	public function fill_query_vars($array) {
		
		$keys = array(
			"error",
			"slug",
			"category_id",
			"product_id",
			"manufacturer_id",
			"paged",
			"items_per_page",
			"order",
			"orderby",
			"in_stock"
		);

		foreach($keys as $key) {
			
			if(!isset($array[$key])) {

				$array[$key] = "";
				
			} // end if
			
		} // end foreach

		$array_keys = array(
			"category__in",
			"category__not_in",
			"category__and"
		);

		foreach($array_keys as $key) {
			
			if(!isset($array[$key])) {
				
				$array[$key] = array();
				
			} // end if
			
		} // end foreach
		
		return $array;
		
	} // end function
	
	/**
	 * Parse a query string and set query type booleans
	 * 
	 * @param string|array $query {
	 *     Optional. Array or string of Query parameters.
	 *     
	 *     @type int          $product_id              Product ID
	 *     @type int          $category_id             Category ID
	 *     @type string       $manufacturer_id         Manufacturer ID
	 *     @type string       $slug                    Product/Category URL slug
	 *     @type array        $category__and           An array of category IDs (AND in)
	 *     @type array        $category__in            An array of category IDs (OR in, no children)
	 *     @type array        $category__not_in        An array of category IDs (NOT in)
	 *     @type bool         $in_stock                Only items in stock?
	 *     @type int          $paged                   The number of the current page
	 *     @type int          $items_per_page          The number of items to query for. Use -1 to request all items
	 *     @type bool         $nopaging                Show all posts (true) or paginate (false). Default false
	 *     @type string       $order                   Designates ascending or descending order of items. Default 'desc'. Accepts 'asc', 'desc'
	 *     @type string       $orderby                 Sort retrieved items by parameter. Accepts 'times_sold', 'name', 'price', 'timestamp_insert' (Default 'times_sold')
	 * }
	 * 
	 * @return boolean
	 */
	public function parse_query($query = "") {
		
		if(!empty($query)) {
			
			$this->init();
			$this->query = $this->query_vars = wp_parse_args($query);
			
		}elseif(!isset($this->query)) {
			
			$this->query = $this->query_vars;
			
		} // end if

		$this->query_vars = $this->fill_query_vars($this->query_vars);
		
		$qv = &$this->query_vars;
		$qv['product_id'] =  absint($qv['product_id']);
		$qv['category_id'] = absint($qv['category_id']);
		$qv['manufacturer_id'] = $qv['manufacturer_id'];
		
		if($qv['product_id'] != "") {
			
			$this->is_product = true;
			$this->is_single = true;
			
		}elseif($qv['category_id'] != "") {
			
			$this->is_category = true;
			
		} // end if

		if($qv['paged']!="" && (intval($qv['paged']) > 1)) {
			
			$this->is_paged = true;
			
		} // end if
		
		$this->is_singular = $this->is_single || $this->is_product;

		if($qv['error'] == "404") {
			
			$this->set_404();
			
		} // end if
		
	} // end function
	
	private function parse_paged($query) {
		
		$pagination = array();
		
		if(empty($query['items_per_page'])) {
			
			$query['items_per_page'] = 10;
			
		} // end if

		if(!isset($query['nopaging'])) {
				
			if($query['items_per_page'] == -1) {
				
				$query['nopaging'] = true;
				$pagination['limit'] = -1;
				
			}else{
				
				$query['nopaging'] = false;
				
			} // end if
				
		} // end if
		
		if(empty($query['nopaging']) && !$this->is_singular) {
				
			$pagination['page'] = absint($query['paged']);
			
			if(!$pagination['page']) {
				
				$pagination['page'] = 1;
				
			} // end if
			
			$pagination['limit'] = $query['items_per_page'];
				
		} // end if

		return $pagination;
		
	} // end function
	
	private function parse_order($query) {
		
		if(!empty($query['order']) && is_string($query['order']) && strtolower($query['order'])=="asc") {
			
			$order = "asc";
			
		}else{
			
			$order = "desc";
			
		} // end if
		
		if(empty($query['orderby']) || $query['orderby']===false) {
				
			$orderby = "times_sold";
			
		}else{
			
			$orderby = addslashes_gpc(urldecode($query['orderby']));
			
			if(!in_array($orderby, array("times_sold","name","price","timestamp_insert"))) {
				
				$orderby = "times_sold";
				
			} // end if
				
		} // end if
		
		return array($orderby, $order);
		
	} // end function

	/**
	 * Retrieve query variable
	 *
	 * @param string $query_var Query variable key
	 * @param mixed  $default   Value to return if the query variable is not set. Default ''
	 * @return mixed
	 */
	public function get($query_var, $default = "") {
		
		if(isset($this->query_vars[$query_var])) {
			
			return $this->query_vars[$query_var];
			
		} // end if

		return $default;
		
	} // end function

	/**
	 * Set query variable
	 *
	 * @param string $query_var Query variable key
	 * @param mixed $value      Query variable value
	 */
	public function set($query_var, $value) {
		
		$this->query_vars[$query_var] = $value;
		
	} // end function
	
	/**
	 * Retrieve the products based on query variables
	 * 
	 * @return array List of products
	 */
	public function get_products() {

		$this->parse_query();

		$q = &$this->query_vars;
		$q = $this->fill_query_vars($q);
		
		// Pagination & Sorting
		
		$pagination = $this->parse_paged($q);		
		list($orderby, $order) = $this->parse_order($q);
		
		// Other filters
			
		if($q['in_stock']) {
			
			ZProduct::setStockFilter(true);
		
		} // end if
		
		if($q['manufacturer_id']) {
			
			$manufacturers = explode(",",$q['manufacturer_id']);
			
			ZProduct::setManufacturerFilter($manufacturers);
			
			if(count($manufacturers)==1) {
				
				$q['manufacturer'] = ZManufacturer::getManufacturer($manufacturers[0]);
				
			} // end if
			
		} // end if
			
		if($q['price']) {
			
			$price = explode("-",$q['price']);
			
			ZProduct::setPriceFilter($price[0],$price[1]);
		
		} // end if
		
		if($q['properties']) {
			
			$filters = array();
			
			foreach($q['properties'] as $property) {
			
				$p = explode("-",$property);
				
				if(!isset($filters[$p[0]])) {
					
					$filters[$p[0]] = array();
					
				} // end if
					
				$filters[$p[0]][] = $p[1];
				
			} // end foreach
			
			foreach($filters as $id=>$options) {
			
				ZProduct::setPropertyFilter($id, $options);
				
			} // end foreach
			
		} // end if
		
		// Load the items

		$this->items = ZProduct::getProducts($pagination, $q['category_id'] ?: null, $orderby, $order, array("get_prices"=>true));
		$this->item_count = $pagination['total_rows'];
		$this->max_num_pages = $pagination['pages'];
				
		return $this->items;
		
	} // end function
	
	/**
	 * Retrieve the product based on query variables
	 * 
	 * @return item
	 */
	public function get_product() {

		$this->parse_query();

		$q = &$this->query_vars;
		$q = $this->fill_query_vars($q);
			
		// Try to load the product
		
		if(($product = ZProduct::getProduct($q['product_id']))!==false) {
			
			$this->items = array($product);
			$this->item_count = 1;
		
			return $product;
			
		} // end if
		
		return false;
		
	} // end function

	/**
	 * Set up the next item and iterate current item index
	 */
	public function next_item() {

		$this->current_item++;

		$this->item = $this->items[$this->current_item];
		return $this->item;
		
	} // end function

	/**
	 * Sets up the current item
	 */
	public function the_item() {
		
		$this->in_the_loop = true;

		$item = $this->next_item();
		
		return $item;
		
	} // end function

	/**
	 * Rewind the items and reset item index
	 */
	public function rewind_items() {
		
		$this->current_post = -1;
		
		if($this->item_count > 0) {
			
			$this->item = $this->items[0];
			
		} // end if
		
	} // end function
	
	/**
	 * Whether there are more products available in the loop
	 *
	 * @return bool True if products are available, false if end of loop
	 */
	public function have_products() {
		
		$item_count = count($this->items); 
		
		if($this->current_item + 1 < $item_count) {
			
			return true;
			
		}elseif($this->current_item + 1 == $item_count && $item_count > 0 ) {
			
			// Do some cleaning up after the loop
			
			$this->rewind_items();
			
		} // end if

		$this->in_the_loop = false;
		
		return false;
		
	} // end function
	
	/**
	 * Is the query for an existing single item?
	 * 
	 * @return bool
	 */
	public function is_single() {
		
		if(!$this->is_single) {
			
			return false;
			
		} // end if

		// TODO Finish
		
		return false;
		
	} // end function
	
	public function is_product() {
		
		return $this->is_product;
		
	} // end function
	
	public function is_category() {
		
		return $this->is_category;
		
	} // end function
	
	/**
	 * Is the query a 404 (returns no results)?
	 * 
	 * @return bool
	 */
	public function is_404() {
		
		return (bool) $this->is_404;
		
	} // end function
	
	/**
	 * Is the query for paged result and not for the first page?
	 * 
	 * @return bool
	 */
	public function is_paged() {

		return (bool) $this->is_paged;
		
	} // end function

	/**
	 * Sets the 404 property
	 */
	public function set_404() {

		$this->init_query_flags();
		$this->is_404 = true;
		
	} // end function
	
} // end class

?>