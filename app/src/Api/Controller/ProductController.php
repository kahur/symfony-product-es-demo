<?php

namespace KH\Api\Controller;

use KH\Api\Hydrator\SimpleEntityHydrator;
use KH\Api\Validators\ArrayValidator;
use KH\Api\Validators\ProductValidator;
use KH\Entity\Category;
use KH\Entity\File;
use KH\Entity\Product;
use KH\Entity\ProductDetail;
use KH\Service\Product\ProductManager;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package KH\Api\Controller
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class ProductController extends BaseController
{
    public function __construct(
    ) {}

    #[Route('/products/{id}', methods: ['GET'])]
    public function displayProduct(Product $product, ProductManager $productManager)
    {
        $product = $productManager->getOne($product->getId());

        return $this->success($product, [
            'groups' => ProductManager::DEFAULT_GROUPS
        ]);
    }

    #[Route('/products', methods: ['POST', 'PATCH'])]
    public function saveProduct(
        Request $request,
        ProductManager $productManager,
        SimpleEntityHydrator $simpleEntityHydrator
    ) {
        $data = json_decode($request->getContent(), 1);
        $product = new Product();
        if ($request->isMethod(Request::METHOD_PATCH)) {
            $id = $data['id'] ?? null;
            if (!$id || !$product = $productManager->getOne($id)) {
                return $this->notFound();
            }

            // let's clear data as if it's not part of payload we don't want it
            $product->clearImages();
            $product->clearCategories();
            $product->clearDetails();
        }

        $product = $simpleEntityHydrator->hydrate(
            $request->getContent(),
            $product, [
                'details' => ProductDetail::class,
                'images' => File::class,
                'files' => File::class,
                'categories' => Category::class
            ], [], true);

        $data = json_decode($request->getContent(), 1);
        $form = $this->createForm(ProductValidator::class, $product);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->errors($form->getErrors());
        }

        try {
            $product = $productManager->saveProduct($product);

            return $this->success($product, [
                'groups' => ProductManager::DEFAULT_GROUPS
            ]);
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    #[Route('/products/{id}', methods: ['DELETE'])]
    public function deleteProduct(Product $product, ProductManager $productManager)
    {
        $productManager->removeProduct($product);

        return $this->success([]);
    }

    #[Route('/products/find', methods: ['POST'])]
    public function findProducts(Request $request, ProductManager $productManager)
    {
        $data = json_decode($request->getContent(), 1);

        return $this->success($productManager->find($data['filter'] ?? [], false), ['groups' => ProductManager::DEFAULT_GROUPS]);
    }
}