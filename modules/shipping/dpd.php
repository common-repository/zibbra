<?php
/**
 * File for the ZShippingAdapterDpd object
 * 
 * @package API_Client_Library\Objects\Shipping\Adapter
 * @copyright Zibbra <info@zibbra.com>
 */

/**
 * ZShippingAdapterDpd
 * 
 * @package API_Client_Library\Objects\Shipping\Adapter
 * @author Alwin Roosen <alwin.roosen@zibbra.com>
 * @version 1.0.0
 */
class ZShippingAdapterDpd extends ZShippingAdapter {
	
	/**
	 * Dispatch the shipping adapter
	 * 
	 * @param ZCustomer $oCustomer
	 * @param ZOrder $oOrder
	 * @see ZShippingAdapter::dispatch()
	 */
	public function dispatch(ZCustomer $oCustomer, ZOrder $oOrder) {
		
		echo "<pre>ZShippingAdapterDpd::dispatch()\n";
		var_dump($oCustomer,$oOrder);
		exit;
		
	} // end function
	
} // end function