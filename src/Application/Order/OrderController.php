<?php
declare(strict_types=1);

namespace App\Application\Order;


use App\Domain\Order\OrderCreator;
use App\Domain\Product\Product;
use App\Domain\Product\Products;
use App\Domain\User;
use App\Infrastructure\ORM\Flusher;
use App\Infrastructure\Responder\JsonResponder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function FMW\uid;
use function FMW\uidFromString;

final class OrderController
{
    private OrderCreator      $orderCreator;
    private Products          $productRepository;
    private JsonResponder     $responder;
    private Flusher           $flusher;
    private LoggerInterface   $logger;

    public function __construct(
        OrderCreator $orderCreator,
        Products $productRepository,
        JsonResponder $responder,
        Flusher $flusher,
        LoggerInterface $logger
    )
    {
        $this->orderCreator      = $orderCreator;
        $this->productRepository = $productRepository;
        $this->responder         = $responder;
        $this->flusher           = $flusher;
        $this->logger            = $logger;
    }

    public function create(Request $request): JsonResponse
    {
        /** @var array $productIdsFromRequest */
        $productIdsFromRequest = $request->get('product_ids') ?? [];
        /** @var Uuid[] $productIds */
        $productIds = array_map(fn(string $uid) => uidFromString($uid), $productIdsFromRequest);
        /** @var Product[] $products */
        $products = $this->productRepository->findByUids($productIds);

        try {
            $order = $this->orderCreator->create(uid(), User::create(), ...$products);
            $this->flusher->flush();
            return $this->responder->data([
                'order_id' => $order->idAsString()
            ]);
        } catch (\DomainException $e) {
            $this->logger->error("Can't create order. Message: {$e->getMessage()}");
            return $this->responder->error([$e->getMessage()]);
        }
    }
}
