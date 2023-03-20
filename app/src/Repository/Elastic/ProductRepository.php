<?php

namespace KH\Repository\Elastic;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use GuzzleHttp\Exception\ClientException;
use KH\Api\Hydrator\SimpleEntityHydrator;
use KH\Entity\Category;
use KH\Entity\File;
use KH\Entity\Product;
use KH\Entity\ProductDetail;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @package KH\Repository\Elastic
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class ProductRepository
{
    const INDEX_NAME = 'products';
    const ID_PREFIX = 'es-';

    public function __construct(
        protected Client $client,
        protected SimpleEntityHydrator $simpleEntityHydrator,
        protected SerializerInterface $serializer
    ) {}

    public function save(Product $product, $update = false)
    {
        try {
            if (!$update) {
                $this->client->index($this->getCreateParams($product));

                return $product;
            }

            $this->client->update($this->getUpdateParams($product))->asArray();

            return $product;
        } catch (ClientException $e) {
            throw $e;
        } catch (MissingParameterException $e) {
            throw $e;
        } catch (ServerResponseException $e) {
            throw $e;
        }
    }

    public function remove(Product $product)
    {
        try {
            $this->client->delete(
                $this->getDeleteParams($product)
            );
        } catch (ClientException $e) {
            throw $e;
        } catch (MissingParameterException $e) {
            throw $e;
        } catch (ServerResponseException $e) {
            throw $e;
        }
    }

    public function find(array $filter = [], bool $hydrateData = true)
    {
        $result = $this->client->search(
            $this->getSearchParams($filter)
        )->asArray()['hits']['hits'];

        if (empty($result)) {
            return [];
        }

        if (!$hydrateData) {
            return $this->mapDataToArray($result);
        }

        return $this->mapListToEntities($result);
    }

    public function findOne(int $id):? Product
    {
        $result = $this->client->search(
            $this->getSearchParams(['id' =>     $id])
        )->asArray()['hits']['hits'];

        $document = $result[0] ?? null;

        if (!$document) {
            return null;
        }

        return $this->mapDataToEntity($document['_source']);
    }

    /**
     * @param array $data
     * @param bool $returnGenerator
     * @return Product[]|\Generator
     */
    protected function mapListToEntities(array $data)
    {
        foreach ($data as $result) {
            yield $this->mapDataToEntity($result['_source']);
        }
    }

    protected function mapDataToArray(array $data)
    {
        foreach ($data as $result) {
            yield $result['_source'];
        }
    }

    /**
     * @param array $data
     * @return Product
     */

    protected function mapDataToEntity(array $data): Product
    {
        return $this->simpleEntityHydrator->hydrate($data, Product::class, [
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
        ], ['product'], false);
    }

    protected function getCreateParams(Product $product)
    {
        $data = $this->serializer->serialize($product, JsonEncoder::FORMAT, [
            'groups' => ['product', 'product_detail', 'file', 'category']
        ]);

        return [
            'index' => self::INDEX_NAME,
            'id' => self::ID_PREFIX . $product->getId(),
            'body' => json_decode($data, 1)
        ];
    }

    protected function getDeleteParams(Product $product)
    {
        return [
            'index' => self::INDEX_NAME,
            'id' => self::ID_PREFIX . $product->getId(),
        ];
    }

    protected function getUpdateParams(Product $product)
    {
        $data = $this->serializer->serialize($product, JsonEncoder::FORMAT, [
            'groups' => ['product', 'product_detail', 'file', 'category']
        ]);

        return [
            'index' => self::INDEX_NAME,
            'id' => self::ID_PREFIX . $product->getId(),
            'body' => [
                'doc' => json_decode($data, 1)
            ]
        ];
    }

    protected function getSearchParams(array $filters = [])
    {
        return [
            'index' => self::INDEX_NAME,
            'body' => [
                'query' =>  [
                    'match' => $filters
                ]
            ]
        ];
    }
}