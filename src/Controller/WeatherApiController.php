<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(Request $request, WeatherUtil $weatherUtil): Response
    {
        $city = $request->query->get('city');
        $country = $request->query->get('country');
        $format = $request->query->get('format', 'json'); 
        $twig = $request->query->getBoolean('twig', false); 

        if (!$city || !$country) {
            return $this->json([
                'error' => 'Missing required parameters: city or country',
            ], 400);
        }

        if (!in_array($format, ['json', 'csv'], true)) {
            return $this->json([
                'error' => 'Invalid format specified. Supported formats: json, csv.',
            ], 400);
        }

        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            if ($format === 'json') {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }

            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }
        }

        if ($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn($m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements),
            ]);
        }

        $csv = "city,country,celsius,fahrenheit,date\n";
        foreach ($measurements as $m) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $city,
                $country,
                $m->getCelsius(),
                $m->getFahrenheit(),
                $m->getDate()->format('Y-m-d')
            );
        }

        return new Response($csv, 200, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
