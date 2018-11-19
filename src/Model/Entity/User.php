<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;


/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 *
 * @property-read int $id
 * @property-read string $login
 */
class User {
    use SmartObject;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="login", type="string", length=127, unique=true)
     * @var string
     */
    private $login;

    /**
     * @ORM\Column(name="password_hash", type="string", length=72)
     * @var string
     */
    private $passwordHash;



    public function __construct(string $login, string $password) {
        $this->login = $login;
        $this->setPassword($password);
    }

    public function getId() : int {
        return $this->id;
    }

    public function getLogin() : string {
        return $this->login;
    }

    public function setPassword(string $password) : void {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordValid(string $password) : bool {
        return password_verify($password, $this->passwordHash);
    }

}
