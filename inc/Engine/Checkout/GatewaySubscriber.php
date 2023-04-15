<?php
namespace SCBWoocommerce\Engine\Checkout;

use DateTime;
use Exception;
use SCBWoocommerce\Dependencies\CoquardCyrilleFreelance\SCBPaymentAPI\Client;
use SCBWoocommerce\Dependencies\CoquardCyrilleFreelance\SCBPaymentAPI\Exceptions\SCBPaymentAPIException;
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
     * @var string
     */
    protected $prefix;

    /**
     * @param Client $client
     * @param Configurations $configurations
     */
    public function __construct(Client $client, Configurations $configurations, string $prefix)
    {
        $this->client = $client;
        $this->configurations = $configurations;
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
            "{$this->prefix}process_payment" => ['process_payment', 7, 2],
            "{$this->prefix}generate_qr_code" => 'generate_qr_code',
            "{$this->prefix}check_payment" => 'check_payment',
            "{$this->prefix}init_client" => 'get_initialized_client'
        ];
    }

    public function generate_qr_code($order_id): string {
        $order = wc_get_order($order_id);
        if (! $order) {
            return '';
        }



        $data = $this->get_initialized_client()->createQRCode($order_id, $order->get_amount());
        $order->add_meta_data('scb_transaction_data', [
            'id' => $data['qrcodeId'],
            'image' => $data['qrImage'],
            'datetime' => time(),
        ]);

        return $data['qrImage'];
    }

    public function process_payment(array $answer, $order_id) {

        $order = wc_get_order($order_id);

        if(! $order) {
            throw new Exception('Payment not complete');
        }

        WC()->session->set('order_key', $order->get_order_key());

        return $answer;
    }

    public function check_payment($order_id) {
        $client = $this->get_initialized_client();

        $order = wc_get_order($order_id);

        if( ! $order ) {
            return false;
        }

        $data = $order->get_meta_data('scb_transaction_data');

        if(!$data || ! key_exists('datetime', $data)) {
            return false;
        }
        try {
            $client->checkTransactionBillPayment($order_id, $order_id, new DateTime($data['datetime']));
            return true;
        } catch (SCBPaymentAPIException $e) {}

        if(! key_exists('id', $data)) {
            return false;
        }

        try {
            $client->checkTransactionBillPayment($data['id']);
            return true;
        } catch (SCBPaymentAPIException $e) {}

        return false;
    }

    public function get_initialized_client(): Client {
        if(! $this->client->is_initialized()) {
            $this->client->initialize($this->configurations);
        }
        return $this->client;
    }
}
