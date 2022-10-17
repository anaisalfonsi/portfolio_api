<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class ApiCreateImageAction extends AbstractController
{
    public function __invoke(Request $request, UserRepository $userRepository): Image
    {
        $user = $userRepository->find(2);

        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $image = new Image();
        $image->file = $uploadedFile;
        $image->setUpdatedAt(new \DateTime('now'));
        $image->setUser($user);

        return $image;
    }
}