<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\User;
use Kdyby\Doctrine\EntityManager;


class UserManager {

    private $em;

    private $users;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->users = $em->getRepository(User::class);
    }


    public function getByLogin(string $login) : ?User {
        return $this->users->findOneBy(['login' => $login]);
    }


    public function createUser(string $login, string $password) : User {
        $user = new User($login, $password);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

}
