<?php

/**
 * Plugin Name: Okane
 * Plugin URI: http://www.wordpress.org
 * Version: 1.0.0
 * Description: Okane lets users buy credits and use them to purchase products.
 * Author: Chase Foster
 * Author URI: http://www.cheisu.com
 * text-domain: okane
 */


//Blocks public users from accessing the plugin's code
if ( ! defined( 'ABSPATH' ) ) {
    die;
 }

//Checks if WooCommerce exists and is enabled. If not, the plugin does not work. 
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins') ) ) ) return;


add_action( 'plugins_loaded', 'okane_payment_init', 11 );

function okane_payment_init() {
    if( class_exists( 'WC_Payment_Gateway' ) )
    {
        class WC_Okane_Payment_Gateway extends WC_Payment_Gateway {

            public function __construct() {
                $this->id = 'okane_payment';
                $this->icon = apply_filters( 'woocommerce_okane_icon', plugins_url( '/assets/icons.png' ) );
                $this->has_fields = false;
                $this->method_title = __( 'Okane Credits', 'okane' );
                $this->method_description = __( 'Allow customers to pay with pre-purchased credits', 'okane' );

                $this->init_forms_fields();
                $this->init_settings();
            }

            public function init_forms_fields() {

                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable Okane Credits', 'okane_payment'),
                        'type' => 'checkbox',
                        'label' => __('Enable or Disable Okane Credits', 'okane_payment'),
                        'default' => 'no'
                    ),
                    'title' => array(
                        'title' => __('Title', 'okane_payment'),
                        'description' => __('This is the payment description your customer will see at checkout.', 'okane_payment'),
                        'type' => 'text',
                        'default' => __('Okane Credits', 'okane_payment'),
                        'desc_tip' => true,
                    ),
                );
            }



        }
    }
}


add_filter( 'woocommerce_payment_gateways', 'add_okane_payment_gateway' );

function add_okane_payment_gateway( $gateways ) {
    $gateways[] = 'WC_Okane_Payment_Gateway';
    return $gateways;
}