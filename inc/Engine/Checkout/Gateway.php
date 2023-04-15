<?php
namespace SCBWoocommerce\Engine\Checkout;

use Exception;
use WC_Payment_Gateway;
class Gateway extends WC_Payment_Gateway
{
    public function __construct() {
        $this->id = 'scb';
        $this->icon = '';
        $this->has_fields = false;
        $this->method_title = __('SCB payment', 'scbwoocommerce');
        $this->method_description = __('Activate SCB payment gateway', 'scbwoocommerce');

        $this->supports = apply_filters('scb_woocommerce_gateway_supports', [
            'products'
        ]);

        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    /**
     * Plugin options, we deal with it in Step 3 too
     */
    public function init_form_fields(){
        $this->form_fields = array(
            'enabled' => array(
                'title'       => __('Enable/Disable', 'scbwoocommerce'),
                'label'       => __('Enable SCB Gateway', 'scbwoocommerce'),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no'
            ),
            'title' => array(
                'title'       => __( 'Title', 'scbwoocommerce' ),
                'type'        => 'text',
                'description' => __( 'Title the client see', 'scbwoocommerce' ),
                'default'     => __( 'Pay with SCB', 'scbwoocommerce' ),
                'desc_tip'    => true,
            ),

            'description' => array(
                'title'       => __( 'Description', 'scbwoocommerce' ),
                'type'        => 'textarea',
                'description' => __( 'Description the client see', 'scbwoocommerce' ),
                'default'     => __( 'Scan the QR Code to pay with SCB Payment', 'scbwoocommerce' ),
                'desc_tip'    => true,
            ),
            'is_sandbox' => array(
                'title'       => __('Test mode', 'scbwoocommerce'),
                'label'       => __('Enable Test Mode', 'scbwoocommerce'),
                'type'        => 'checkbox',
                'description' => __('Place the payment gateway in test mode using test API keys.', 'scbwoocommerce'),
                'default'     => 'yes',
                'desc_tip'    => true,
            ),
            'application_id' => array(
                'title'       => __('ID from the application', 'scbwoocommerce'),
                'type'        => 'text'
            ),
            'application_secret' => array(
                'title'       => __('Secret from the application', 'scbwoocommerce'),
                'type'        => 'password'
            ),
            'merchant' => array(
                'title'       => __('Merchant ID','scbwoocommerce'),
                'type'        => 'text',
            ),
            'biller' => array(
                'title'       => __('Biller ID', 'scbwoocommerce'),
                'type'        => 'text'
            ),
            'terminal' => array(
                'title'       => __('Terminal ID', 'scbwoocommerce'),
                'type'        => 'text'
            ),
            'prefix' => array(
                'title'       => __('Prefix', 'scbwoocommerce'),
                'type'        => 'text'
            )
        );

    }

    public function is_available()
    {
        $cached = wp_cache_get('scb_woocommerce_is_available');

        if($cached !== false) {
            return $cached;
        }

        $is_available = parent::is_available();
        if( ! $is_available) {
            wp_cache_set('scb_woocommerce_is_available', (int) $is_available);
            return $is_available;
        }

        try {
            do_action('scb_woocommerce_init_client');
        } catch (Exception $exception) {
            wp_cache_set('scb_woocommerce_is_available', 0);
            return false;
        }

        wp_cache_set('scb_woocommerce_is_available', 1);
        return true;
    }

    public function process_payment( $order_id ) {

        return apply_filters('scb_woocommerce_process_payment', array(
            'result'=>'success',
        ),
            $order_id
        );
    }
}