<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Adds a new user to the database',
)]
class AddUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $usernameQuestion = new Question('Enter the username: ');
        $username = $helper->ask($input, $output, $usernameQuestion);

        $passwordQuestion = new Question('Enter the password: ');
        $passwordQuestion->setHidden(true);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $user = new User();
        $user->setUsername($username);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']); 

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>User successfully added!</info>');

        return Command::SUCCESS;
    }
}
