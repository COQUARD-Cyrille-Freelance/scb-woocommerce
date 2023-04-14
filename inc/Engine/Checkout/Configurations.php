<?php

namespace SCBWoocommerce\Engine\Checkout;

class Configurations implements \SCBWoocommerce\Dependencies\CoquardCyrilleFreelance\SCBPaymentAPI\Configurations
{

    public function __construct(Gateway $gateway) {
        $this->merchant = $gateway->get_option('merchant');
        $this->terminal = $gateway->get_option('terminal');
        $this->biller = $gateway->get_option('biller');
        $this->application_id = $gateway->get_option('application_id');
        $this->application_secret = $gateway->get_option('application_secret');
        $this->prefix = $gateway->get_option('prefix');
        $this->language = get_locale();
        $this->is_sandbox = 'yes' === $gateway->get_option( 'is_sandbox' );
    }

    /**
     * @var string
     */
    protected $merchant;

    /**
     * @var string
     */
    protected $terminal;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $biller;

    /**
     * @var string
     */
    protected $application_id;

    /**
     * @var string
     */
    protected $application_secret;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var bool
     */
    protected $is_sandbox;

    /**
     * @inheritDoc
     */
    public function getMerchant(): string
    {
        return $this->merchant;
    }

    /**
     * @inheritDoc
     */
    public function getTerminal(): string
    {
        return $this->terminal;
    }

    /**
     * @inheritDoc
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @inheritDoc
     */
    public function getBiller(): string
    {
        return $this->biller;
    }

    /**
     * @inheritDoc
     */
    public function getApplicationId(): string
    {
        return $this->application_id;
    }

    /**
     * @inheritDoc
     */
    public function getApplicationSecret(): string
    {
        return $this->application_secret;
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @inheritDoc
     */
    public function isSandbox(): bool
    {
        return $this->is_sandbox;
    }
}