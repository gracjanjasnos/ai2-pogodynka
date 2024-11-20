<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtil
{
    private MeasurementRepository $measurementRepository;
    private LocationRepository $locationRepository;

    public function __construct(MeasurementRepository $measurementRepository, LocationRepository $locationRepository)
    {
        $this->measurementRepository = $measurementRepository;
        $this->locationRepository = $locationRepository;
    }

    /**
     * Pobiera pomiary na podstawie lokalizacji.
     *
     * @param Location $location
     * @return Measurement[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurementRepository->findByLocation($location);
    }

    /**
     * Pobiera pomiary na podstawie kodu kraju i nazwy miasta.
     *
     * @param string $countryCode
     * @param string $cityName
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $cityName): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $cityName,
        ]);

        if (!$location) {
            return []; 
        }

        return $this->getWeatherForLocation($location);
    }
}
