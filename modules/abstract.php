<?php

abstract class Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	/**
	 * @var Zibbra_Plugin_Controller
	 */
	protected $controller;

	/**
	 * @var ZLibrary_Adapter_Interface
	 */
	protected $adapter;

	/**
	 * Zibbra_Plugin_Module_Abstract constructor.
	 *
	 * @param Zibbra_Plugin_Controller $controller
	 */
	public function __construct(Zibbra_Plugin_Controller $controller) {
		
		$this->controller = $controller;
		$this->adapter = ZLibrary::getInstance()->getAdapter();
		
	} // end function
	
	public function register_ajax() {

		return $this->doAjax();
		
	} // end function
	
	public function process_post() {

		return $this->doPost();
		
	} // end function

	public function generate_rewrite_rules(WP_Rewrite $wp_rewrite) {

		$rules = $this->getRewriteRules();

		$wp_rewrite->rules = $rules + (array) $wp_rewrite->rules;

	} // end function

	public function query_vars($public_query_vars) {

		array_push($public_query_vars, "zibbra");

		$query_vars = array_merge($public_query_vars, $this->getQueryVars());

		return $query_vars;

	} // end function

	public function template_include(WP_Query $wp_query) {

		global $z_query;

		$module_name = $this->getModuleName();

		if($wp_query->get("zibbra") === $module_name) {

			// Get the template name

			$template_name = $this->doOutput($wp_query, $z_query);

			// Set the title

			$this->set_title($this->getPageTitle());

			// Return template name

			return $template_name;

		} // end if

		return false;

	} // end function
	
	private function set_title($title) {

		$module_name = $this->getModuleName();

		add_filter("the_title",function ($old_title, $id) use($title, $module_name) {

			if(is_zibbra() && $id==0) {

				return $title;

			} // end if

			return $old_title;

		}, 10, 2); // end add_filter

		add_filter("wp_title", function ($old_title) use($title) {

			return $title." | ".$old_title;

		}, 100); // end add_filter

	} // end function
//
} // end class