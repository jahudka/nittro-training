<?php

declare(strict_types=1);

namespace App\Security;

use App\Model\Manager\UserManager;
use Nette\NotSupportedException;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;


class SimpleAuthenticator implements IAuthenticator {

    private $userRepository;


    public function __construct(UserManager $userRepository) {
        $this->userRepository = $userRepository;
    }


    public function authenticate(array $credentials) {
        if (count($credentials) !== 2) {
            throw new NotSupportedException();
        }

        list($login, $password) = $credentials;
        $user = $this->userRepository->getByLogin($login);

        if (!$user || !$user->isPasswordValid($password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        return new Identity($user->id);
    }

}
