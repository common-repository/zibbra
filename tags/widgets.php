<?php

function get_zibbra_minicart($popup=true, $links=true, $click=false) {
	
	the_widget("Zibbra_Plugin_Widget_Minicart", "popup=".($popup ? "Y" : "N")."&links=".($links ? "Y" : "N")."&click=".($click ? "Y" : "N"));

} // end function

function get_zibbra_login($title=null, $popup=true, $click=false, $icon=null) {
	
	the_widget("Zibbra_Plugin_Widget_Login", "title=".$title."&popup=".($popup ? "Y" : "N")."&click=".($click ? "Y" : "N").($icon!==null ? "&icon=".urlencode($icon) : ""));

} // end function

function get_zibbra_bestsellers($title=null, $maxval=3, $thumbsize=100) {
	
	the_widget("Zibbra_Plugin_Widget_Bestsellers", "title=".$title."&maxval=".$maxval."&thumbsize=".$thumbsize);

} // end function

function get_zibbra_brands($title=null, $size=60) {
	
	the_widget("Zibbra_Plugin_Widget_Brands", "title=".$title."&size=".$size);

} // end function

function get_zibbra_notify() {
	
	the_widget("Zibbra_Plugin_Widget_Notify");

} // end function

function get_zibbra_menu($show_home=true, $depth=1) {
	
	the_widget("Zibbra_Plugin_Widget_Menu", "override=N&show_home=".($show_home ? "Y" : "N")."&depth=".$depth);

} // end function

function get_zibbra_newsletter($title, $description, $input, $button, $icon) {

	the_widget("Zibbra_Plugin_Widget_Newsletter", "title=".$title."&description=".$description."&input=".$input."&button=".$button."&icon=".$icon);

} // end function