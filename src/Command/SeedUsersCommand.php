<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:seed-users', description: 'Tworzy konta do lokalnego cwiczenia LAB E.')]
final class SeedUsersCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('password', null, InputOption::VALUE_REQUIRED, 'Haslo kont testowych.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $password = $input->getOption('password');
        if (!is_string($password) || strlen($password) < 8) {
            $output->writeln('Podaj --password z co najmniej 8 znakami.');
            return Command::INVALID;
        }
        foreach (['admin' => ['ROLE_ADMIN'], 'Jakub Rossa' => ['ROLE_ADMIN'], 'czytelnik' => ['ROLE_USER']] as $name => $roles) {
            if ($this->em->getRepository(User::class)->findOneBy(['username' => $name])) {
                $output->writeln($name . ': konto juz istnieje.');
                continue;
            }
            $user = (new User())->setUsername($name)->setRoles($roles);
            $user->setPassword($this->hasher->hashPassword($user, $password));
            $this->em->persist($user);
            $output->writeln($name . ': konto utworzone.');
        }
        $this->em->flush();
        return Command::SUCCESS;
    }
}
