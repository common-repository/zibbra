<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;

$z_query->set("return", urlencode(site_url("/zibbra/checkout/")));

get_template_part("account", "details");

?>