<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:Verbosity',
    description: 'Add a short description for your command',
)]
class VerbosityCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->writeln('This is writeln()');

        if($io->isVerbose())
        {
            $io->writeln('It is Verbose()');
        }

        if($io->isVeryVerbose())
        {
            $io->writeln('It is Very Verbose()');
        }

        if($io->isDebug())
        {
            $io->writeln('It is Debug()');
        }


        return Command::SUCCESS;
    }
}
