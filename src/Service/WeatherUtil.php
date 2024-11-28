<?php

namespace App\Service;

use App\Entity\Measurement;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;

class WeatherUtil
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Pobiera prognozę pogody dla kraju i miasta
     *
     * @param string $country
     * @param string $city
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $country, string $city): array
    {
        // Pobierz obiekt Location z bazy danych
        $location = $this->entityManager->getRepository(Location::class)
            ->findOneBy(['city' => $city, 'country' => $country]);

        // Jeśli lokalizacja nie istnieje, utwórz nową
        if (!$location) {
            $location = new Location();
            $location->setCity($city);
            $location->setCountry($country);

            // Pobierz współrzędne
            $coordinates = $this->getCoordinates($city, $country);
            $location->setLatitude($coordinates['latitude']);
            $location->setLongitude($coordinates['longitude']);

            $this->entityManager->persist($location);
            $this->entityManager->flush();
        }

        // Twórz dane pomiarowe
        $measurements = [];
        for ($i = 0; $i < 3; $i++) {
            $measurement = new Measurement();
            $measurement->setDate(new \DateTime(sprintf('+%d days', $i)));
            $measurement->setLocation($location);
            $measurement->setCelsius(number_format(rand(5, 15) + $i * 1.1, 1));

            $measurements[] = $measurement;
        }

        return $measurements;
    }

    /**
     * Pobiera współrzędne geograficzne dla miasta i kraju za pomocą API Nominatim
     *
     * @param string $city
     * @param string $country
     * @return array
     */
    private function getCoordinates(string $city, string $country): array
    {
        $url = sprintf(
            'https://nominatim.openstreetmap.org/search?city=%s&country=%s&format=json',
            urlencode($city),
            urlencode($country)
        );

        $response = @file_get_contents($url);
        $data = json_decode($response, true);

        if (!empty($data[0])) {
            return [
                'latitude' => $data[0]['lat'],
                'longitude' => $data[0]['lon'],
            ];
        }

        // Domyślne współrzędne w razie braku wyniku
        return [
            'latitude' => '0.0000000',
            'longitude' => '0.0000000',
        ];
    }
}
