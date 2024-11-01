<?php

class Zibbra_Plugin_Module_Shipping_Sprintpack extends Zibbra_Plugin_Module_Shipping_Abstract implements Zibbra_Plugin_Module_Shipping_Interface {

    /**
     * Name of the adapter
     *
     * @var string
     */
    const ADAPTER_NAME = "sprintpack";

    /**
     * @return string
     */
    public function getAdapterName() {

        return self::ADAPTER_NAME;

    } // end function

    public function onSelect() {

        ZLibrary::getInstance()->getAdapter()->setSessionValue("shipping.complete", true);

        wp_redirect(site_url("/zibbra/checkout/continue/"));
        exit;

    } // end function

} // end class