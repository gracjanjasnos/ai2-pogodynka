<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Displays weather forecast for a specific location based on its ID.',
)]
class WeatherLocationCommand extends Command
{
    private LocationRepository $locationRepository;
    private WeatherUtil $weatherUtil;

    public function __construct(LocationRepository $locationRepository, WeatherUtil $weatherUtil)
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->weatherUtil = $weatherUtil;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'ID of the location to fetch the weather for');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $locationId = $input->getArgument('id');

        $location = $this->locationRepository->find($locationId);
        if (!$location) {
            $io->error(sprintf('Location with ID %d not found.', $locationId));
            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));
        if (empty($measurements)) {
            $io->writeln('No measurements available for this location.');
        } else {
            foreach ($measurements as $measurement) {
                $io->writeln(sprintf(
                    "\t%s: %s°C",
                    $measurement->getDate()->format('Y-m-d'),
                    $measurement->getCelsius()
                ));
            }
        }

        return Command::SUCCESS;
    }
}
