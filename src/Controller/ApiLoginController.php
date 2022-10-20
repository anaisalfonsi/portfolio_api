<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login',  methods: ['POST'])]
    public function index(IriConverterInterface $iriConverter, SerializerInterface $serializer): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }

        $user = (object) [
            'id' => $this->getUser()->getId(),
            'pseudo' => $this->getUser()->getPseudo(),
            'images' => $this->getUser()->getImages()
        ];

       return new Response($serializer->serialize($user, 'jsonld'), 200, [
            'location' => $iriConverter->getIriFromResource($this->getUser()),
            'Access-Control-Allow-Origin' => $this->getParameter('front_domain_name'),
            'Access-Control-Allow-Methods' => 'POST',
            'Access-Control-Allow-Credentials' => 'true'
        ]);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Should not be reached');
    }

}
