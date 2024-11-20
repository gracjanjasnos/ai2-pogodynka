<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather_city', requirements: ['country' => '[A-Z]{2}'], defaults: ['country' => null])]
    public function city(string $city, ?string $country, WeatherUtil $util): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if (empty($measurements)) {
            return new Response("No measurements found for location: " . $city . ($country ? ", " . $country : ""));
        }

        $location = $measurements[0]->getLocation(); 

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
