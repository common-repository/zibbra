<?php

class Zibbra_Plugin_Cache {
	
	const DEFAULT_CACHE = 86400;
	
	public static function load($func, $args=array(), $expiry=self::DEFAULT_CACHE) {
		
		if(is_array($func)) {
			
			$className = $func[0];
			$function = $func[1];
			$call = $className."::".$function;
			
		}else{
			
			$call = $func;
			
		} // end if
		
		if(!is_array($args)) {
			
			$args = array($args);
			
		} // end if
		
		$languagecode = Zibbra_Plugin_Controller::getInstance()->getLibrary()->getAdapter()->getLanguagecode();
		
		$transient = "zibbra_".$languagecode."_".md5($call."(".serialize($args).")");
		
		//syslog(LOG_DEBUG,$call."(".serialize($args).")"." ".$transient);		
		//delete_transient($transient);
		
		$cache = get_transient($transient);
		
		if(!$cache) {
			
			$data = call_user_func_array($call, $args);
			set_transient($transient,serialize($data), $expiry);
			
		}else{
			
			$data = unserialize($cache);
			
		} // end if
		
		return $data;
		
	} // end function
	
} // end class

?>
