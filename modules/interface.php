<?php

interface Zibbra_Plugin_Module_Interface {

	/**
	 * Returns the page title for this module
	 *
	 * @return string
	 */
	public function getPageTitle();

	/**
	 * Returns the module name to be used in URL and rewrite rules
	 *
	 * @return string
	 */
	public function getModuleName();

	/**
	 * Returns an array of query vars to be used in the URI rewrite
	 *
	 * @return string[]
	 */
	public function getQueryVars();

	/**
	 * Returns an array of rewrite rules for this module
	 *
	 * @return array
	 */
	public function getRewriteRules();

	/**
	 * Perform AJAX call
	 *
	 * @return bool
	 */
	public function doAjax();

	/**
	 * Perform HTTP POST actions
	 *
	 * @return bool
	 */
	public function doPost();

	/**
	 * Returns the template name to output
	 *
	 * @param WP_Query $wp_query
	 * @param Zibbra_Plugin_Query $z_query
	 *
	 * @return string|array|false
	 */
	public function doOutput(WP_Query $wp_query, Zibbra_Plugin_Query $z_query);

} // end interface