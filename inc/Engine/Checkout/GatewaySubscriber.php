<?php
namespace SCBWoocommerce\Engine\Checkout;

use SCBWoocommerce\Dependencies\CoquardCyrilleFreelance\SCBPaymentAPI\Client;
use SCBWoocommerce\Dependencies\LaunchpadCore\EventManagement\SubscriberInterface;
class GatewaySubscriber implements SubscriberInterface {

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Configurations
     */
    protected $configurations;

    /**
     * @param Client $client
     * @param Configurations $configurations
     */
    public function __construct(Client $client, Configurations $configurations)
    {
        $this->client = $client;
        $this->configurations = $configurations;
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

        ];
    }

    public function process_payment() {

    }

    public function check_payment() {

    }
}
