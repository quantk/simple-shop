<?php
declare(strict_types=1);

namespace App\Application\Payment;


use App\Domain\Order\Orders;
use App\Domain\Payment\PaymentService;
use App\Infrastructure\ORM\Flusher;
use App\Infrastructure\Responder\JsonResponder;
use Money\Money;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function FMW\uidFromString;

final class PaymentController
{
    private PaymentService  $paymentService;
    private Orders          $orderRepository;
    private JsonResponder   $responder;
    private Flusher         $flusher;
    private LoggerInterface $logger;

    public function __construct(
        PaymentService $paymentService,
        Orders $orderRepository,
        JsonResponder $responder,
        Flusher $flusher,
        LoggerInterface $logger
    )
    {
        $this->paymentService  = $paymentService;
        $this->orderRepository = $orderRepository;
        $this->responder       = $responder;
        $this->flusher         = $flusher;
        $this->logger          = $logger;
    }

    public function pay(Request $request, string $orderId): JsonResponse
    {
        $userSum = Money::RUB($request->get('sum') ?? 0);

        $order = $this->orderRepository->find(uidFromString($orderId));
        if ($order === null) {
            return $this->responder->error([
                'Order not found'
            ], 404);
        }

        try {
            $this->paymentService->pay($order, $userSum);
            $this->flusher->flush();

            return $this->responder->data();
        } catch (\DomainException $e) {
            $this->logger->error("Can't pay order. Message: {$e->getMessage()}");
            return $this->responder->error([$e->getMessage()]);
        }
    }
}
