<?php

$zibbra_category = null;
$zibbra_categories_tag = new stdClass();
$zibbra_categories_tag->categories = null;
$zibbra_categories_tag->count = null;
$zibbra_categories_tag->current = null;
$zibbra_categories_tag->loop = true;

function have_zibbra_categories() {

	global $zibbra_categories_tag, $zibbra_category;

	if(is_null($zibbra_categories_tag->categories)) {

		$zibbra_categories_tag->current = -1;
		$zibbra_categories_tag->categories = ZCategory::getCategories(false,true);

		if($zibbra_categories_tag->categories === false) {

			$zibbra_categories_tag->categories = array();
			$zibbra_categories_tag->count = 0;

		}else{

			$zibbra_categories_tag->count = count($zibbra_categories_tag->categories);

		} // end if

	} // end if

	if($zibbra_categories_tag->current + 1 < $zibbra_categories_tag->count) {

		return true;

	} elseif ( $zibbra_categories_tag->current + 1 == $zibbra_categories_tag->count && $zibbra_categories_tag->count > 0 ) {

		$zibbra_categories_tag->current = -1;

		if ( $zibbra_categories_tag->count > 0 ) {

			$zibbra_category = $zibbra_categories_tag->categories[0];

		} // end if

	} // end if

	$zibbra_categories_tag->loop = false;
	return false;

} // end function

function the_zibbra_category() {

	global $zibbra_categories_tag, $zibbra_category;

	$zibbra_categories_tag->loop = true;
	$zibbra_categories_tag->current++;

	$zibbra_category = $zibbra_categories_tag->categories[$zibbra_categories_tag->current];

} // end function

function get_catalog_link($params=array()) {

	/** @var Zibbra_Plugin_Query $z_query */
	
	global $z_query;
	
	// Get the controller and active module

	$controller = Zibbra_Plugin_Controller::getInstance();
	$module = $controller->getActiveModule();
	
	// Make sure the catalog module is the active one
	
	if($module->getModuleName() === Zibbra_Plugin_Module_Catalog::MODULE_NAME) {
		
		// Start building the URI
		
		$uri = "/zibbra/catalog/";
		$args = array();
		
		if(($slug = $z_query->get("slug",false))!==false && !empty($slug)) {
			
			$uri .= $slug."/";
			
		} // end if
		
		if(($manufacturerid = $z_query->get("manufacturer_id",false))!==false && $manufacturerid!=0) {
			
			$uri .= "manufacturer/".$manufacturerid."/";
			
		} // end if
		
		if(isset($params['page'])) {

			$uri .= "page/".$params['page']."/";
			
		} // end if
		
		if(isset($params['limit']) || isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_LIMIT])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_LIMIT] = isset($params['limit']) ? $params['limit'] : (int) $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_LIMIT];
			
		} // end if
		
		if(isset($params['sort_type']) || isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_SORT_TYPE])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_SORT_TYPE] = isset($params['sort_type']) ? $params['sort_type'] : $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_SORT_TYPE];
			
		} // end if
		
		if(isset($params['sort_dir']) || isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_SORT_DIR])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_SORT_DIR] = isset($params['sort_dir']) ? $params['sort_dir'] : $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_SORT_DIR];
			
		} // end if
		
		if(isset($params['in_stock']) || isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_IN_STOCK])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_IN_STOCK] = isset($params['in_stock']) ? $params['in_stock'] : $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_IN_STOCK];
			
		} // end if
		
		if(isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_PRICE])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_PRICE] = $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_PRICE];
			
		} // end if
		
		if(isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_PROPERTIES])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_PROPERTIES] = $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_PROPERTIES];
			
		} // end if
		
		if(isset($_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_MANUFACTURERS])) {

			$args[Zibbra_Plugin_Module_Catalog::QUERY_VAR_MANUFACTURERS] = $_GET[Zibbra_Plugin_Module_Catalog::QUERY_VAR_MANUFACTURERS];
			
		} // end if
		
		if(count($args) > 0) {

		    // Sanitize/escape all URL parameters with http_build_query. Invalid input will be handled by the Zibbra API anyways
			
			$uri .= "?".http_build_query($args);
			
		} // end if
		
		return site_url($uri);
		
	} // end if
	
	// Return generic URL to the catalog

	return site_url("/zibbra/catalog");

} // end function

function have_zibbra_bestsellers() {

	$bestsellers = Zibbra_Plugin_Cache::load(array("ZProduct","getBestsellers"), array(Zibbra_Plugin_Widget_Bestsellers::DEFAULT_LIMIT, Zibbra_Plugin_Widget_Bestsellers::DEFAULT_THUMB_SIZE));

	return count($bestsellers) > 0;

} // end function