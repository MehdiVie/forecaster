<?php

namespace App\Command;

use App\Entity\Forecast;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'Forecast:location-name',
    description: 'Get a forecast for given country code and location name!',
)]
class ForecastLocationNameCommand extends Command
{
    public function __construct(
        private LocationRepository $locationRepository,
        private ForecastRepository $forecastRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED,'Country Code')
            ->addArgument('cityName', InputArgument::REQUIRED,'City Name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');

        if ($io->isVeryVerbose()) {
            $io->writeln("Running command with {countryCode} , {cityName}");
        }

        $location = $this->locationRepository->findOneBy([
            'countryCode' => $countryCode,
            'name' => $cityName
        ]);

        if(!$location){
            throw new \Exception('location not found!');
        }

        $forecasts = $this->forecastRepository->findForForecast($location);

        $io->title('Forecast for $countryCode, $cityName');

        $forecastsArray=[];
        foreach($forecasts as $forecast) {
            $forecastsArray[]=[  
                $forecast->getDate()->format('Y-m-d'),
                $forecast->getCelcius(),
                $forecast->getFlTemperatureCelsius()  
            ];
            // $io->listing(["{$forecast->getDate()->format('Y-m-d')} : {$forecast->getCelcius()} deg C"]);
        }

        $io->horizontalTable([
            'Date',
            'Temperature',
            'Feel Like Temperature'
        ], $forecastsArray);

        return Command::SUCCESS;
    }
}
