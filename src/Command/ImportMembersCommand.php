<?php

namespace App\Command;

use App\Entity\Member;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:import-members', description: 'Import a CSV of members',)]
class ImportMembersCommand extends Command
{
    public function __construct(
        public KernelInterface $kernel,
        public EntityManagerInterface $entityManager,
        public ValidatorInterface $validator,
        string $name = null,
    ){
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The path of the CSV to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');

        $path = $this->kernel->getProjectDir() . '/data/' . $name;

        if (! file_exists($path)){
            $io->error('File not found!');
            return Command::INVALID;
        }

        $open = fopen($path, "r");

        $membersToImport = [];

        while (($data = fgetcsv($open, separator: ';')) !== FALSE)
        {
            $membersToImport[] = $data;
        }

        fclose($open);

        $amount = count($membersToImport);

        $io->info("Found {$amount} members to import");

        foreach ($membersToImport as $row => $memberToImport) {
            $member = new Member();
            $member->setBirthDate(DateTimeImmutable::createFromFormat('!d/m/Y', trim($memberToImport[0])));
            $member->setMembershipNumber(trim($memberToImport[1]));

            $errors = $this->validator->validate($member);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $io->warning("Row {$row} - {$error->getPropertyPath()}: {$error->getMessage()}");
                }

                continue;
            }

            $this->entityManager->persist($member);
            $this->entityManager->flush();
        }

        $io->success('Successfully imported all members');

        return Command::SUCCESS;
    }
}
