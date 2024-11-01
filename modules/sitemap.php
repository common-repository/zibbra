<?php

class Zibbra_Plugin_Module_Sitemap extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "sitemap";

	public function getPageTitle() {

		return null;

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra.xml/?$' => 'index.php?zibbra='.self::MODULE_NAME
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		$links = array();

		$this->loadProducts($links);
		$this->loadCategories($links);
		$this->loadBrands($links);

		$sitemap = new DOMDocument();
		$sitemap->formatOutput = true;

		$root = $sitemap->createElement("urlset");
		$sitemap->appendChild($root);

		$root_attr = $sitemap->createAttribute("xmlns");
		$root->appendChild($root_attr);

		$root_attr_text = $sitemap->createTextNode("http://www.sitemaps.org/schemas/sitemap/0.9");
		$root_attr->appendChild($root_attr_text);

		foreach($links as $link) {

			$url = $sitemap->createElement("url");
			$root->appendChild($url);

			$loc = $sitemap->createElement("loc");
			$lastmod = $sitemap->createElement("lastmod");
			$changefreq = $sitemap->createElement("changefreq");
			$priority = $sitemap->createElement("priority");

			$loc->appendChild($sitemap->createTextNode($link->loc));
			$lastmod->appendChild($sitemap->createTextNode($link->lastmod));
			$changefreq->appendChild($sitemap->createTextNode($link->changefreq));
			$priority->appendChild($sitemap->createTextNode($link->priority));

			$url->appendChild($loc);
			$url->appendChild($lastmod);
			$url->appendChild($changefreq);
			$url->appendChild($priority);

		} // end foreach

		header("Content-Type: text/xml");
		echo $sitemap->saveXML();
		exit;

	} // end function
	
	private function loadCategories(&$links=array()) {
		
		$arrCategories = Zibbra_Plugin_Cache::load(array("ZCategory","getCategories"),array(),1800);
		
		foreach($arrCategories as $oCategory) {

			/** @var ZCategory $oCategory */
			
			$url = new stdClass();
			$url->loc = site_url("/zibra/category/".$oCategory->getSlug()."/");
			$url->lastmod = date("Y-m-d");
			$url->changefreq = "weekly";
			$url->priority = 0.8;
			
			$links[] = $url;
			
		} // end foreach
		
	} // end function
	
	private function loadBrands(&$links=array()) {
		
		$arrManufacturers = Zibbra_Plugin_Cache::load(array("ZManufacturer","getManufacturers"));
		
		foreach($arrManufacturers as $oManufacturer) {

			/** @var ZManufacturer $oManufacturer */
			
			$url = new stdClass();
			$url->loc = site_url("/zibbra/catalog/manufacturer/".$oManufacturer->getManufacturerid()."/");
			$url->lastmod = date("Y-m-d");
			$url->changefreq = "monthly";
			$url->priority = 0.5;
			
			$links[] = $url;
			
		} // end foreach
		
	} // end function
	
	private function loadProducts(&$links=array()) {
		
		$pagination = array(
			"page"=>1,
			"limit"=>1000
		);
		
		$arrProducts = ZProduct::getProducts($pagination);
		
		foreach($arrProducts as $oProduct) {
			
			if(($d = $oProduct->getDateTimeUpdate())===false) {
				
				$d = new DateTime();
				
			} // end if
			
			$url = new stdClass();
			$url->loc = site_url("/zibbra/product/".$oProduct->getSlug()."/");
			$url->lastmod = $d->format("Y-m-d");
			$url->changefreq = "weekly";
			$url->priority = 1;
			
			$links[] = $url;
			
		} // end foreach
		
	} // end function

} // end class