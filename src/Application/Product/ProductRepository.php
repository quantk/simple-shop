<?php
declare(strict_types=1);

namespace App\Application\Product;


use App\Domain\Product\Product;
use App\Domain\Product\Products;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

final class ProductRepository implements Products
{
    private EntityManagerInterface $entityManager;
    private EntityRepository       $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        /** @var EntityRepository $repository */
        $repository       = $entityManager->getRepository(Product::class);
        $this->repository = $repository;
    }

    public function add(Product $order): void
    {
        $this->entityManager->persist($order);
    }

    public function find(Uuid $id): ?Product
    {
        /** @var Product|null $product */
        $product = $this->repository->find($id);
        return $product;
    }

    /**
     * @param array|Uuid[] $uids
     * @return Product[]
     */
    public function findByUids(array $uids): array
    {
        $qb = $this->repository->createQueryBuilder('product');
        $qb
            ->where('product.id IN (:ids)')
            ->setParameter('ids', $uids);

        /** @var Product[] $result */
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
