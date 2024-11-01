<?php

function is_customer_logged_in() {
	
	if(is_user_logged_in()) {
		
		if(ZCustomer::isAuthenticated()) {
			
			return true;
			
		} // end if
		
	} // end if

	return false;

} // end function

?>