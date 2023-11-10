<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'app_index', methods: ['GET'])]
final class IndexController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse("Hello");
    }

}
