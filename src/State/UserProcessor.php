<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Annotation
 */
class UserProcessor implements ProcessorInterface
{
    private $decorated;
    private $entityManager;
    private $passwordHasher;

    public function __construct(ProcessorInterface $decorated, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->decorated = $decorated;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $result = $this->decorated->process($data, $operation, $uriVariables, $context);
        $this->persistPassword($data);
        return $result;
    }

    /**
     * @param User $user
     */
    private function persistPassword(User $user)
    {
        dd("je persiste le mot de passe");
        die;
        if ($user->getPlainPassword()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );

            $user->eraseCredentials();
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
