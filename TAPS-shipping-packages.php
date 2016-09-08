<?php

class TAPS_Shipping_Packages {

	// Singleton instance.
    private static $instance;

	//existing packages native WC (one single package with all items grouped together)
	//protected $packages = array();
	public $packages = array();

	// Create a new singleton instance.
    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get Settings for Restrictions Table.
     * Called directly from the woocommerce_cart_shipping_packages filter.
     *
     * @access public
     * @return void
     */
    public function generate_packages( $packages ) {
	    // Build out the packages
	  
	    //Set up arrays for Free, Fixed, and Freight items
	    $free_items    = array();
	    $fixed_items   = array();
	    $freight_items = array();
	    $regular_items = array();
	    
	    // Sort free, fixed, and freight from regular
	    foreach ( WC()->cart->get_cart() as $item ) {
	        if ( $item['data']->needs_shipping() ) {
	        	if ( $item['data']->get_shipping_class() == 'free-shipping' ) {
	                $free_items[] = $item;
	            } 
	            elseif ( $item['data']->get_shipping_class() == 'fixed-shipping' ) {
	                $fixed_items[] = $item;
	            } 
	            elseif ( $item['data']->get_shipping_class() == 'freight-shipping' ) {
	                $freight_items[] = $item;
	        	}
	        	else {
	                $regular_items[] = $item;
	            }
	        }
	    }

	    // Put inside free package
	    if ( $free_items ) {
	        $packages[] = array(
	        	'contents'        => $free_items,
	            'ship_via'        => array( 'free_shipping', 'local_pickup' ),
	            'contents_cost'   => array_sum( wp_list_pluck( $free_items, 'line_total' ) ),
	            'applied_coupons' => WC()->cart->applied_coupons,
	            'destination'     => array(
	                'country'   => WC()->customer->get_shipping_country(),
	                'state'     => WC()->customer->get_shipping_state(),
	                'postcode'  => WC()->customer->get_shipping_postcode(),
	                'city'      => WC()->customer->get_shipping_city(),
	                'address'   => WC()->customer->get_shipping_address(),
	                'address_2' => WC()->customer->get_shipping_address_2()
	            )
	        );
	    }
	    
	    // Put inside fixed package
	    if ( $fixed_items ) {
	        $packages[] = array(
	        	'contents'        => $fixed_items,
	            'ship_via'        => array( 'flat_rate' ),
	            'contents_cost'   => array_sum( wp_list_pluck( $fixed_items, 'line_total' ) ),
	            'applied_coupons' => WC()->cart->applied_coupons,
	            'destination'     => array(
	                'country'   => WC()->customer->get_shipping_country(),
	                'state'     => WC()->customer->get_shipping_state(),
	                'postcode'  => WC()->customer->get_shipping_postcode(),
	                'city'      => WC()->customer->get_shipping_city(),
	                'address'   => WC()->customer->get_shipping_address(),
	                'address_2' => WC()->customer->get_shipping_address_2()
	            )
	        );
	    }

	    // Put inside freight package
	    if ( $freight_items ) {
	        $packages[] = array(
	            'contents'        => $freight_items,
	            'ship_via'        => array( 'taps_freight' ),
	            'contents_cost'   => array_sum( wp_list_pluck( $freight_items, 'line_total' ) ),
	            'applied_coupons' => WC()->cart->applied_coupons,
	            'destination'     => array(
	                'country'   => WC()->customer->get_shipping_country(),
	                'state'     => WC()->customer->get_shipping_state(),
	                'postcode'  => WC()->customer->get_shipping_postcode(),
	                'city'      => WC()->customer->get_shipping_city(),
	                'address'   => WC()->customer->get_shipping_address(),
	                'address_2' => WC()->customer->get_shipping_address_2()
	            )
	        );
	    }

	    // Put inside standard package
	    if ( $regular_items ) {
	        $packages[] = array(
	            'contents'        => $regular_items,
	        	'ship_via'        => array( 'fedex', 'usps', 'local_pickup' ),
	            'contents_cost'   => array_sum( wp_list_pluck( $regular_items, 'line_total' ) ),
	            'applied_coupons' => WC()->cart->applied_coupons,
	            'destination'     => array(
	                'country'   => WC()->customer->get_shipping_country(),
	                'state'     => WC()->customer->get_shipping_state(),
	                'postcode'  => WC()->customer->get_shipping_postcode(),
	                'city'      => WC()->customer->get_shipping_city(),
	                'address'   => WC()->customer->get_shipping_address(),
	                'address_2' => WC()->customer->get_shipping_address_2()
	            )
	        );
	    }

	    // We skip over the first package which is the native WC package and is duplicating all the cart items
        $output = array_slice($packages, 1);

        return $output;

	} //end generate_packages


} //end TAPS_shipping_packages