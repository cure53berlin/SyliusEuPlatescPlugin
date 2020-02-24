<?php

declare(strict_types=1);

namespace Infifni\SyliusEuPlatescPlugin\Payum;

final class SyliusApi
{
    public const SANDBOX_ENVIRONMENT = 'sandbox';
    public const SECURE_ENVIRONMENT = 'secure';
    public const SANDBOX_MERCHANT_ID = 'testaccount';
    public const SANDBOX_KEY = '00112233445566778899AABBCCDDEEFF';
    public const PAYMENT_URL = 'https://secure.euplatesc.ro/tdsprocess/tranzactd.php';

    /**
     * @var string
     */
    private $environment;
    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $key;

    public function __construct(string $environment, string $merchantId, string $key)
    {
        $this->environment = $environment;
        $this->merchantId = $merchantId;
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}