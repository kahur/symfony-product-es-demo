<?php

namespace KH\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    #[Route('/', 'kh_api_home')]
    public function home()
    {
        return new JsonResponse(['status' => 'ok']);
    }
}