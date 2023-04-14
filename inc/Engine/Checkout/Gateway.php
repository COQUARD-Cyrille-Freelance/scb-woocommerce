<?php

namespace SCBWoocommerce\Engine\Checkout;
use Exception;
use SCBWoocommerce\Dependencies\CoquardCyrilleFreelance\SCBPaymentAPI\Client;
use WC_Payment_Gateway;
class Gateway extends WC_Payment_Gateway
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Configurations
     */
    protected $configurations;

    public function __constructor() {
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
                'description' => __( 'Titre du paiement que le client voit', 'scbwoocommerce' ),
                'default'     => __( 'Pay with SCB', 'scbwoocommerce' ),
                'desc_tip'    => true,
            ),

            'description' => array(
                'title'       => __( 'Description', 'scbwoocommerce' ),
                'type'        => 'textarea',
                'description' => __( 'Description du paiement que le client voit', 'scbwoocommerce' ),
                'default'     => __( 'Vous allez être redirigé vers la page de paiement de SCB', 'scbwoocommerce' ),
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
        $is_available = parent::is_available();
        if( ! $is_available) {
            return $is_available;
        }

        try {
            do_action('scb_woocommerce_init_client');
        } catch (Exception $exception) {
            return false;
        }
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