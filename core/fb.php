<?php

class Zibbra_Plugin_Fb {
	
	private $trackingid;
	private $rendered = false;
	
	const GLOBAL_OBJECT = "fbq";
	
	public function __construct($trackingid) {
		
		$this->trackingid = $trackingid;
		
	} // end function
	
	public function __toString() {
		
		// General HTML for the FB tracking code
		
		$html = "<!-- Facebook Pixel Code -->\n";
        $html .= "<script>\n";
        $html .= "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
        $html .= "n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;\n";
        $html .= "n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;\n";
        $html .= "t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,\n";
        $html .= "document,'script','//connect.facebook.net/en_US/fbevents.js');\n";
        $html .= "fbq('init', '".$this->trackingid."');\n";
        $html .= "fbq('track', 'PageView');</script>\n";
        $html .= "<noscript>\n";
        $html .= "<img height=\"1\" width=\"1\" style=\"display:none\" src=\"https://www.facebook.com/tr?id=".$this->trackingid."&ev=PageView&noscript=1\"\n";
        $html .= "/></noscript>\n";
		$html .= "<!-- End Facebook Pixel Code -->\n";
		
		// Mark as rendered
		
		$this->rendered = true;
		
		// Return html code for insertion into the page
		
		return $html;
		
	} // end function

    /**
     * @param string $url URL to redirect to when tracking done
     * @param ZOrder $order
     * @return string Modified URL for tracking (if enabled, otherwise original URL)
     * @throws ZException
     */
    public static function trackOrderComplete($url, ZOrder $order = null) {

        $trackingid = get_option("zibbra_fb_tracking_id", null);

        if(!empty($trackingid)) {

            $adapter = ZLibrary::getInstance()->getAdapter();

            if(!is_null($order)) {

                $orderid = $order->getOrderid();

            }else{

                $orderid = $adapter->getSessionValue("order.id",false);

            } // end if

            if($orderid) {

                $url = site_url("/zibbra/track/fbq/".$orderid."/")."?return=".base64_encode($url);

            } // end if

        } // end if

        return $url;

    } // end function
	
} // end class