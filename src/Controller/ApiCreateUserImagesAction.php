<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
final class ApiCreateUserImagesAction extends AbstractController
{
    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher) : User
    {
        $uploadedFiles = $request->files->get('file');

        if (!$uploadedFiles) {
            throw new BadRequestHttpException('"file" is required');
        }
        dd($request);
        // $user = $this->getUser();

        $user = $request->attributes->get('data');

        if (!($user instanceof User)) {
            throw new \RuntimeException('User object is expected.');
        }

        if ($user->getPassword()) {
            $user->setPassword($user->getPassword());
        } else if (!$user->getPassword() && $user->getPlainPassword()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );
            $user->eraseCredentials();
        }

        foreach ($uploadedFiles as $uploadedFile) {
            $image = new Image();
            $image->file = $uploadedFile;
            $image->setUpdatedAt(new \DateTime());
            $image->setUser($user);
            $user->addImage($image);
        }

        return $user;
    }
}