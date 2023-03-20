<?php

namespace KH\Service\Product;


use Doctrine\Persistence\ManagerRegistry;
use KH\Entity\Product;

/**
 * @package KH\Service\Product
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class ProductService
{
    public function __construct(
        protected ManagerRegistry $managerRegistry
    ) {}

    /**
     * @param Product $product
     * @return Product
     */
    public function save(Product $product)
    {
        if ($product->getId()) {
            $product->setUpdatedAt(new \DateTime());
        }

        $this->managerRegistry->getManager()->persist($product);
        $this->managerRegistry->getManager()->flush();

        return $product;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function remove(Product $product)
    {
        $this->managerRegistry->getManager()->remove($product);
        $this->managerRegistry->getManager()->flush();
    }

    /**
     * @return array|Product[]
     */
    public function getProducts()
    {
        return $this->managerRegistry->getRepository(Product::class)->findAll();
    }
}