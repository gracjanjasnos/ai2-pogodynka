<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country}', name: 'app_weather_city', requirements: ['country' => '[A-Z]{2}'], defaults: ['country' => null])]
    public function city(string $city, ?string $country, EntityManagerInterface $entityManager, MeasurementRepository $repository): Response
    {
        $location = $entityManager->getRepository(Location::class)->findOneBy([
            'city' => $city,
            'country' => $country,
        ]);

        if (!$location) {
            return new Response("No measurements found for location: " . $city . ($country ? ", " . $country : ""));
        }

        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
