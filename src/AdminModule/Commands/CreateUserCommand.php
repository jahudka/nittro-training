<?php

declare(strict_types=1);

namespace App\AdminModule\Commands;

use App\Model\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateUserCommand extends Command {

    private $userManager;


    public function __construct(UserManager $userManager) {
        parent::__construct();
        $this->userManager = $userManager;
    }


    protected function configure() : void {
        $this->setName('user:create')
            ->addArgument('login', InputArgument::REQUIRED, 'The login for the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password for the new user');
    }


    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $login = $input->getArgument('login');
        $password = $input->getArgument('password');
        $user = $this->userManager->createUser($login, $password);

        $output->writeln(sprintf('User #%d created.', $user->getId()));

        return 0;
    }

}
