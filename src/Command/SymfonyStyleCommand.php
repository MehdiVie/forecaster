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
    name: 'app:symfony-style',
    description: 'Demonstrates various Styles',
)]
class SymfonyStyleCommand extends Command
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
        
        // $io -> writeln("This is writeln()");
        // $io->title('This is a title()');
        // $io -> section('This is section()');

        // $name = $io->ask('What is your name?');
        // $output->writeln($name);

        // $password = $io->askHidden('What is your password?');
        // $output->writeln($password);
        // $result =$io->confirm('Are you sure?');
        // $output -> writeln($result ? 'Yes' : 'No'); 
        // $result=$io->choice('What is the correct answer: ',['A', 'B', 'C']);
        // $output -> writeln($result);
        // $items = ['apple', 'pear' , 'pineapple'];
        // $io->listing($items);
        // $io->table(['Column-1','Column-2'],[
        //     ['1-1','1-2'],
        //     ['2-1','2-2'],
        //     ['3-1','3-2'],
        // ]);
        $items = ['apple', 'pear' , 'pineapple','plum'];

        $io->progressStart(4);

        foreach ($items as $item) {
            $io->progressAdvance();
            sleep(2);
        }

        $io->progressFinish();

        return Command::SUCCESS;
    }
}
