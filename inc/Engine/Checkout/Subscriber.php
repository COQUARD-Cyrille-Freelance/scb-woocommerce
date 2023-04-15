<?php
namespace SCBWoocommerce\Engine\Checkout;
use SCBWoocommerce\Dependencies\LaunchpadCore\EventManagement\SubscriberInterface;

class Subscriber implements SubscriberInterface {

    /**
     * @var string
     */
    protected $plugin_name;

    /**
     * @var string
     */
    protected $assets_url;

    /**
     * @var string
     */
    protected $plugin_version;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $template_path;

    /**
     * @param string $plugin_name
     * @param string $assets_url
     * @param string $plugin_version
     * @param string $prefix
     * @param string $template_path
     */
    public function __construct(string $plugin_name, string $assets_url, string $plugin_version, string $prefix, string $template_path)
    {
        $this->plugin_name = $plugin_name;
        $this->assets_url = $assets_url;
        $this->plugin_version = $plugin_version;
        $this->prefix = $prefix;
        $this->template_path = $template_path;
    }

    /**
     * Returns an array of events that this subscriber wants to listen to.
     *
     * The array key is the event name. The value can be:
     *
     *  * The method name
     *  * An array with the method name and priority
     *  * An array with the method name, priority and number of accepted arguments
     *
     * For instance:
     *
     *  * array('hook_name' => 'method_name')
     *  * array('hook_name' => array('method_name', $priority))
     *  * array('hook_name' => array('method_name', $priority, $accepted_args))
     *  * array('hook_name' => array(array('method_name_1', $priority_1, $accepted_args_1)), array('method_name_2', $priority_2, $accepted_args_2)))
     *
     * @return array
     */
    public function get_subscribed_events() {
        return [
            'wp_enqueue_scripts' => 'enqueue_scripts',
            'woocommerce_payment_gateways' => 'add_gateway_class',
            "wp_ajax_{$this->prefix}check_payment" => 'ajax_check_payment_status',
            "woocommerce_thankyou" => 'register_template',
        ];
    }

    public function enqueue_scripts()
    {
        if(! is_order_received_page()) {
            return;
        }

        wp_enqueue_script("{$this->prefix}checkout", $this->assets_url . '/js/app.js', array('jquery'), $this->plugin_version, false);

        wp_localize_script(
            "{$this->prefix}checkout",
            "{$this->prefix}checkout_data",
            [
                'nonce'      => wp_create_nonce( "{$this->prefix}checkout" ),
                'ajax_endpoint' => admin_url('/admin-ajax.php'),
            ]
        );
    }

    public function add_gateway_class( $gateways ) {
        $gateways[] = Gateway::class; // your class name is here
        return $gateways;
    }

    public function ajax_check_payment_status() {
        check_ajax_referer( "{$this->prefix}checkout" );
        var_dump(WC()->session->get('order_key'));
        //wc_get_order_id_by_order_key(WC()->session->)

    }

    public function register_template() {
        require_once $this->template_path . '/thank-you.php';
    }
}
