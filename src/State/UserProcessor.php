<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Annotation
 */
final class UserProcessor implements ProcessorInterface
{
    private $decorated;
    private $passwordHasher;

    public function __construct(ProcessorInterface $decorated, UserPasswordHasherInterface $passwordHasher)
    {
        $this->decorated = $decorated;
        $this->passwordHasher = $passwordHasher;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []) : mixed
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->passwordHasher->hashPassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            $data->eraseCredentials();
        }
        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}
