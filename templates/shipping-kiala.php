<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

?>

<p><?php echo __("Please select the Kiala access point from the map below, as the pickup location for your order.", Zibbra_Plugin::LC_DOMAIN); ?></p>

<div id="zibbra_shipping_iframe" class="embed-responsive embed-responsive-16by9">

	<iframe src="<?php echo $kiala_url; ?>"></iframe>

</div>