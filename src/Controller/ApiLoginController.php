<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function index(IriConverterInterface $iriConverter): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }
        return new Response(null, 204, [
            'location' => $iriConverter->getIriFromResource($this->getUser())
        ]);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Should not be reached');
    }
}
