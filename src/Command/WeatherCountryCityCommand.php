<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:country-city',
    description: 'Fetch weather for a given country code and city name',
)]
class WeatherCountryCityCommand extends Command
{
    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        parent::__construct();
        $this->weatherUtil = $weatherUtil;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code (e.g., PL, US)')
            ->addArgument('cityName', InputArgument::REQUIRED, 'City name (e.g., Szczecin)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');

        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);

        if (empty($measurements)) {
            $io->error(sprintf('No weather data found for city: %s, country: %s', $cityName, $countryCode));
            return Command::FAILURE;
        }

        $location = $measurements[0]->getLocation();
        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));

        foreach ($measurements as $measurement) {
            $io->writeln(sprintf(
                "\tDate: %s, Temperature: %s°C",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getCelsius()
            ));
        }

        return Command::SUCCESS;
    }
}
