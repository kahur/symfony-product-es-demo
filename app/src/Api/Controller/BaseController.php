<?php

namespace KH\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @package KH\Api\Controller
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class BaseController extends AbstractController
{
    protected function success($data, array $groups = []): JsonResponse
    {
        return $this->json(
            ['status' => 'ok', 'data' => $data],
            context: $groups
        );
    }

    protected function error($message, $data = null, $code = 500): JsonResponse
    {
        return $this->json(['status' => 'error', 'message' => $message, 'data' => $data], $code);
    }

    protected function notFound(): Response
    {
        return new Response('<h1>404</h1>',status: 404);
    }

    protected function errors(FormErrorIterator $formErrorIterator): JsonResponse
    {

        return $this->error('Invalid request data.', [
            'errors' => (string) $formErrorIterator
        ], 400);
    }
}