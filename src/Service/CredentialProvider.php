<?php

namespace App\Service;

use App\Entity\Credential;
use Doctrine\ORM\EntityManagerInterface;

class CredentialProvider
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCredential(string $name): ?Credential
    {
        return $this->em->getRepository(Credential::class)->findOneBy(['name' => $name]);
    }

    public function getKey(string $name): ?string
    {
        $cred = $this->getCredential($name);
        return $cred ? $cred->getKey() : null;
    }

    public function getUsername(string $name): ?string
    {
        $cred = $this->getCredential($name);
        return $cred ? $cred->getUsername() : null;
    }

    public function getPassword(string $name): ?string
    {
        $cred = $this->getCredential($name);
        return $cred ? $cred->getPassword() : null;
    }
}