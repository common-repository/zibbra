<?php
/**
 * File for the Menu widget
 * 
 * @author Alwin Roosen <alwin.roosen@zibbra.com>
 * @package Wordpress\Plugin
 */

/**
 * Class for the Menu widget
 * 
 * @author Alwin Roosen <alwin.roosen@zibbra.com>
 * @package Wordpress\Plugin
 */
class Zibbra_Plugin_Widget_Menu extends Zibbra_Plugin_Widget_Abstract {
	
	private $override;
	private $show_home;
	private $depth;
	private $location;
	private $replaced = false;
	
	public function __construct() {

		$this->name = __("Zibbra Menu", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Navigation menu based on your catalog categories.", Zibbra_Plugin::LC_DOMAIN);

		parent::__construct();
		
		$this->check_override();
		
	} // end function

	/**
	 *  Load all instances of this plugin and check if we need to override the main navigation
	 */
	private function check_override() {
		
		$instances = $this->get_settings();
		
		foreach($instances as $instance) {
			
			if($instance['override']=="Y") {
		
				$this->show_home = $instance['show_home'];
				$this->depth = (int) $instance['depth'];
				$this->location = $instance['location'];
	
				add_filter("wp_nav_menu", array(&$this, "show_menu"), 10, 2);
				add_filter("wp_page_menu", array(&$this, "show_menu"), 10, 2);
				
				break;
			
			} // end if
			
		} // end foreach
		
	} // end function
	
	public function form($instance) {
		
		$menu_locations = get_nav_menu_locations();
		
		$override = isset($instance['override']) ? $instance['override'] : "N";
		$show_home = isset($instance['show_home']) ? $instance['show_home'] : "Y";
		$depth = isset($instance['depth']) ? $instance['depth'] : 1;
		$location = isset($instance['location']) ? $instance['location'] : $menu_locations[0];
		
		include(sprintf("%s/widgets/menu-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		$instance = array();
				
		$instance['override'] = (!empty($new_instance['override'])) ? $new_instance['override'] : "N";
		$instance['show_home'] = (!empty($new_instance['show_home'])) ? $new_instance['show_home'] : "Y";
		$instance['depth'] = (!empty($new_instance['depth'])) ? $new_instance['depth'] : 1;
		$instance['location'] = $instance['override']=="Y" ? (!empty($new_instance['location']) ? $new_instance['location'] : "primary") : false;
	
		return $instance;
		
	} // end function
        
	public function widget($args, $instance=null) {
		
		$this->override = isset($instance['override']) ? $instance['override'] : "N";
		$this->show_home = isset($instance['show_home']) ? $instance['show_home'] : "Y";
		$this->depth = isset($instance['depth']) ? $instance['depth'] : 1;
		$this->location = $this->override=="Y" ? (isset($instance['location']) ? $instance['location'] : "primary") : false;

		if($this->override=="N") {
		
			$this->show_menu();
			
		} // end if
	
	} // end function

	/**
	 * @param string $html
	 * @param object $menu
	 * @return string|null
     */
	public function show_menu($html=null, $menu=null) {
		
		if(($html===null && $menu===null) || ($menu->theme_location==$this->location)) {
			
			$arrCategories = Zibbra_Plugin_Cache::load(array("ZCategory","getCategories"),array(),1800);
		
			$vars = array(
				"show_home" => $this->show_home,
				"depth" => $this->depth,
				"location" => $this->location,
				"arrCategories" => $arrCategories
			);
			
			$this->template_include("menu", $args=array(), $vars);

		}else{
			
			return $html;
			
		} // end if

		return null;
		
	} // end function
	
} // end class

?>