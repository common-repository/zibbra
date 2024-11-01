<?php

class Zibbra_Plugin_Widget_Abstract extends WP_Widget {
	
	public $name;
	public $description;
	
	public function __construct() {
		
		if(empty($this->name)) {
			
			$this->name = get_class($this);
			
		} // end if
		
		if(empty($this->description)) {
			
			$this->description = $this->name;
			
		} // end if
		
		parent::__construct(false, $this->name, array("description"=>$this->description));
		
		add_action("init", array($this, "process_post"));
		
	} // end function
	
	public function form($instance) {
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
	} // end function
	
	public function widget($args, $instance) {
		
	} // end function
	
	public function process_post() {
		
	} // end function
	
	protected function template_include($template, $args=array(), $vars=array()) {
		
		global $z_query;
		
		$vars = array_merge($vars,array("args"=>$args));
		
		$z_query->query_vars = array_merge($z_query->query_vars, $vars);
		
		get_zibbra_template_part("widget", $template);
		
	} // end function
	
} // end class

?>