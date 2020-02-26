<?php
declare(strict_types=1);

namespace Tests\Application\Payment;


use App\Application\Payment\PaymentProcessor;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

final class PaymentProcessorTest extends TestCase
{
    public function testProcessWithNullUrl()
    {
        $paymentProcessor = new PaymentProcessor(null, new Client());
        $this->expectException(\RuntimeException::class);
        $paymentProcessor->process();
    }

    public function testProcessFailed()
    {
        $client = $this->createMock(ClientInterface::class);
        $client->method('request')->willReturn(new Response(400));
        $paymentProcessor = new PaymentProcessor('http://ya.ru', $client);
        $this->expectException(\RuntimeException::class);
        $paymentProcessor->process();
    }

    public function testProcess()
    {
        $client = $this->createMock(ClientInterface::class);
        $client->method('request')->willReturn(new Response(200));
        $paymentProcessor = new PaymentProcessor('http://ya.ru', $client);
        $paymentProcessor->process();
        static::assertTrue(true);
    }
}
