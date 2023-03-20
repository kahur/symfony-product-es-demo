<?php

namespace KH\Service\Product;

use KH\Api\Hydrator\SimpleEntityHydrator;
use KH\Api\Service\RedisStorage;
use KH\Entity\Category;
use KH\Entity\File;
use KH\Entity\Product;
use KH\Entity\ProductDetail;
use KH\Repository\Elastic\ProductRepository;
use KH\Service\CrudService;

/**
 * @package KH\Service\Product
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class ProductManager
{
    const DEFAULT_GROUPS = [
        'product',
        'product_detail',
        'file',
        'category'
    ];

    public function __construct(
        protected CrudService          $crudService,
        protected RedisStorage         $redisStorage,
        protected ProductRepository    $elasticProductRepository,
        protected SimpleEntityHydrator $entityHydrator
    )
    {
    }

    /**
     * @param Product $product
     * @param int[] $fileIds
     * @param int[] $categoryIds
     * @return Product
     */
    public function saveProduct(Product $product): ?Product
    {
        try {
            $update = $product->getId() ? true : false;

            $this->crudService->save($product);
            $this->elasticProductRepository->save($product, $update);

            // invalidate cache
            $this->redisStorage->update('product-' . $product->getId(), $product, self::DEFAULT_GROUPS);
            $this->redisStorage->clearByTags(['search']);

            return $product;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function removeProduct(Product $product): bool
    {
        try {
            $this->redisStorage->remove('product-' . $product->getId());
            $this->crudService->remove($product);
            $this->elasticProductRepository->remove($product);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getOne(int $id): ?Product
    {
        $cachedProduct = $this->redisStorage->get('product-' . $id);
        if (!$cachedProduct) {
            $product = $this->crudService->findOne(Product::class, $id);
            if (!$product) {
                return null;
            }

            $this->redisStorage->save('product-' . $product->getId(), $product, serializeGroups: self::DEFAULT_GROUPS);

            return $product;
        }

        return $this->hydrateDataToEntity($cachedProduct);

    }

    public function find(array $filter = [], bool $hydrateData = true)
    {
        if (!empty($filter)) {
            $key = $this->getSearchKey($filter);
            if ($result = $this->redisStorage->get($key)) {
                $data = json_decode($result, 1);

                return $hydrateData ? $this->hydrateList($data) : $data;
            }

            $items = $this->elasticProductRepository->find($filter, $hydrateData);
            if (empty($items)) {
                return [];
            }

            $this->redisStorage->save($key, $items, ['search'],self::DEFAULT_GROUPS);
            $items = json_decode($this->redisStorage->get($key), 1);

            return $hydrateData ? $this->hydrateList($items) : $items;
        }

        return $this->elasticProductRepository->find($filter, $hydrateData);
    }

    protected function hydrateList(array $data)
    {
        foreach ($data as $item) {
            yield $this->hydrateDataToEntity($item);
        }
    }

    protected function hydrateDataToEntity($data, bool $fetchEntity = false): Product
    {
        return $this->entityHydrator->hydrate($data, Product::class, [
                'files' => [
                    'class' => File::class,
                    'groups' => ['file']
                ],
                'categories' => [
                    'class' => Category::class,
                    'groups' => ['category']
                ],
                'details' => [
                    'class' => ProductDetail::class,
                    'groups' => ['product_detail']
                ]
            ], ['product'], $fetchEntity
        );
    }

    protected function getSearchKey(array $filter)
    {
        $search = [
            ' ',
            '-',
            '_'
        ];

        $replace = [
            '',
            '',
            ''
        ];

        $key = [];

        foreach ($filter as $k => $value) {
            $key[] = $k;
            $key[] = strtolower(str_replace($search, $replace, $value));
        }

        return implode('-', $key);
    }

}