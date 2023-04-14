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
    protected $assets_uri;

    /**
     * @var string
     */
    protected $plugin_version;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param string $plugin_name
     * @param string $assets_uri
     * @param string $plugin_version
     * @param string $prefix
     */
    public function __construct(string $plugin_name, string $assets_uri, string $plugin_version, string $prefix)
    {
        $this->plugin_name = $plugin_name;
        $this->assets_uri = $assets_uri;
        $this->plugin_version = $plugin_version;
        $this->prefix = $prefix;
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
            'admin_init' => 'enqueue_scripts',
            'woocommerce_payment_gateways' => 'add_gateway_class',
        ];
    }

    public function enqueue_scripts()
    {
        if(! is_checkout()) {
            return;
        }

        wp_enqueue_script("{$this->prefix}checkout", $this->assets_uri . '/js/app.js', array('jquery'), $this->plugin_version, false);

        wp_localize_script(
            "{$this->prefix}checkout",
            "{$this->prefix}checkout_data",
            [
                'nonce'      => wp_create_nonce( 'rocket-ajax' ),
                'ajax_endpoint' => admin_url('/admin-ajax.php'),
            ]
        );
    }

    public function add_gateway_class( $gateways ) {
        $gateways[] = Gateway::class; // your class name is here
        return $gateways;
    }
}
