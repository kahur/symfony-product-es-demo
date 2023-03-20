<?php

namespace KH\Api\Controller;

use KH\Api\Validators\CategoryValidator;
use KH\Entity\Category;
use KH\Service\CrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package KH\Api\Controller
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class CategoryController extends BaseController
{
    #[Route('/categories/{id}', methods: ['GET'])]
    public function getCategory(Category $category)
    {
        return $this->success($category, ['groups' => ['category', 'category_detail', 'product']]);
    }

    #[Route('/categories', methods: ['GET'])]
    public function getCategories(CrudService $crudService)
    {
        return $this->success(
            $crudService->findAll(Category::class),
            ['groups' => 'category']
        );
    }

    #[Route('/categories', methods: ['POST', 'PATCH'])]
    public function saveCategory(Request $request, CrudService $crudService)
    {
        $data = json_decode($request->getContent(), 1);
        $category = new Category();
        if ($request->isMethod(Request::METHOD_PATCH)) {
            $id = $data['id'] ?? null;
            if (!$category = $crudService->findOne(Category::class, $id)) {
                return $this->notFound();
            }
        }

        $form = $this->createForm(CategoryValidator::class, $category);
        $form = $form->submit($data);
        if (!$form->isValid()) {
            return $this->errors($form->getErrors());
        }

        try {
            $crudService->save($category);

            return $this->success($category->toArray());
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    #[Route('/categories/{id}', methods: ['DELETE'])]
    public function deleteCategory(Category $category, CrudService $crudService)
    {
        try {
            $crudService->delete($category);

            return $this->success([]);
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}