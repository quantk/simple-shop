<?php
declare(strict_types=1);

namespace App\Application\Payment;


use GuzzleHttp\ClientInterface;

class PaymentProcessor
{
    private ?string $paymentServiceUrl;
    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    public function __construct(?string $paymentServiceUrl, ClientInterface $client)
    {
        $this->paymentServiceUrl = $paymentServiceUrl;
        $this->client            = $client;
    }

    public function process(): void
    {
        if ($this->paymentServiceUrl === null) {
            throw new \RuntimeException('Payment service url is null');
        }

        $client = $this->client;
        /** @noinspection PhpUnhandledExceptionInspection */
        $response = $client->request('get', $this->paymentServiceUrl);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Payment error');
        }
    }
}
