<?php

namespace App\Entity;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user__user")
 * @ORM\HasLifecycleCallbacks()
 */

class SonataUserUser extends BaseUser
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string[]
     */
    public function getRealRoles(): array
    {
        return $this->getRoles();
    }

    public function setRealRoles(array $roles): self
    {
        $this->setRoles($roles);
        return $this;
    }
}

