<?php

class Zibbra_Plugin_Module_Track extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "track";

	const QUERY_VAR_ACTION = "track";
	const QUERY_VAR_ADAPTER = "adapter";
	const QUERY_VAR_ORDERID = "orderid";

	const ADAPTER_FACEBOOK = "fbq";

	public function getPageTitle() {

		return null;

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR_ACTION,
			self::QUERY_VAR_ADAPTER,
			self::QUERY_VAR_ORDERID
		];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/track/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME."&".self::QUERY_VAR_ADAPTER.'=$matches[1]&'.self::QUERY_VAR_ORDERID.'=$matches[2]&'.self::QUERY_VAR_ACTION.'=return'
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		switch($wp_query->get(self::QUERY_VAR_ADAPTER)) {

			case self::ADAPTER_FACEBOOK: {

				$orderid = $wp_query->get(self::QUERY_VAR_ORDERID);
				$return = isset($_GET['return']) ? base64_decode(esc_url_raw($_GET['return'], ['http','https'])) : false;
				$trackingid = get_option("zibbra_fb_tracking_id",null);

				if(!empty($trackingid) && $return) {

					$fb = new Zibbra_Plugin_Fb($trackingid);
					$order = ZOrder::load($orderid);
					$amount = $order->getAmountIncl();

					echo $fb;
					echo "<script>";
					echo "if(typeof(fbq)===\"function\") { fbq('track', 'Purchase', {value: '".number_format($amount,2,".","")."', currency: 'EUR'}); }";
					echo "setTimeout(function() { location.href = '".$return."'; },100);";
					echo "</script>";
					exit;

				} // end if

			};break;

		} // end switch

		wp_redirect(site_url("/"));
		exit;

	} // end function

} // end class