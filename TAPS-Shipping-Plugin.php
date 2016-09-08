<?php
/*
Plugin Name: TAPS Shipping Plugin
Plugin URI: https://github.com/academe/wc-multiple-packages
Description: Configure how products are grouped into shipping packages for WooCommerce.
Current Author: James Fry james@theaquaponicsource.com
Author URI: https://github.com/jfbuildstuff
Original Author: Jason Judge jason@academe.co.uk
Original Original Plugin Author: Erica Dion erica@bolderelements.net (http://www.bolderelements.net/)
Version: 1.0

Copyright: © 2014 Bolder Elements, © 2015 Academe Computing, 2016 The Aquaponic Source
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Check if WooCommerce is active.
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    if ( ! class_exists( 'TAPS_Shipping_Packages' ) ) {
        // Include the main class.
        // We will keep classes each defined in their own files.
        include_once( dirname(__FILE__) . '/classes/TAPS-shipping-packages.php' );
    }

    // Add the filter to generate the packages.
    // At the moment, this plugin will discard any packages already created and then
    // generate its own from scratch. A future enhancement would see this plugin
    // taking the existing packages, and perhaps splitting them down further if
    // necessary, then adding the linking meta fields to the result.
    add_filter(
        'woocommerce_cart_shipping_packages',
        array( TAPS_Shipping_Packages::get_instance(), 'generate_packages' )
    );
}