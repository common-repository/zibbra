<?php

/**
 * Created by PhpStorm.
 * User: alwinroosen
 * Date: 24.11.15
 * Time: 13:23
 */
class Zibbra_Plugin_Recaptcha {

    private static $loaded = false;

    public static function initRecaptcha() {

        $recaptcha_key = get_option("zibbra_recaptcha_key", null);
        $recaptcha_secret = get_option("zibbra_recaptcha_secret", null);

        if(!empty($recaptcha_key) && !empty($recaptcha_secret)) {

            wp_enqueue_script("wp-plugin-zibbra-recaptcha", "https://www.google.com/recaptcha/api.js");
            self::$loaded = true;

        } // end if

    } // end function

    public static function showRecaptcha() {

        if(self::$loaded) {

            $recaptcha_key = get_option("zibbra_recaptcha_key", null);

            if(!empty($recaptcha_key)) {

                echo '<section class="zibbra-register-recaptcha">';
                echo '<div class="g-recaptcha" data-sitekey="'.$recaptcha_key.'"></div>';
                echo '</section>';

            } // end if

        } // end if

    } // end function

    public static function verifyRecaptcha() {

        $recaptcha_key = get_option("zibbra_recaptcha_key", null);
        $recaptcha_secret = get_option("zibbra_recaptcha_secret", null);

        if(!empty($recaptcha_key) && !empty($recaptcha_secret)) {

            if(!isset($_POST['g-recaptcha-response'])) {

                return false;

            } // end if

            $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".urlencode($_POST['g-recaptcha-response']);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, TRUE);
            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response, true);

            return $response['success'];

        } // end if

        return true;

    } // end function

} // end class