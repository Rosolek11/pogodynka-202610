<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^[a-z]{2}$/iD'])]
        string $country,
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^[^\x00-\x1f]+$/uD'])]
        string $city,
        WeatherUtil $weather,
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^(json|csv)$/D'])]
        string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        $measurements = $weather->getWeatherForCountryAndCity($country, $city);
        $data = [
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(static fn (Measurement $m): array => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ];

        if ($twig) {
            $contentType = $format === 'json' ? 'application/json' : 'text/plain';
            return $this->render(
                'weather_api/index.'.$format.'.twig',
                $data + ['withFahrenheit' => true],
                new Response(headers: ['Content-Type' => $contentType.'; charset=UTF-8']),
            );
        }

        if ($format === 'csv') {
            $lines = ['city,country,date,celsius,fahrenheit'];
            foreach ($data['measurements'] as $row) {
                $lines[] = implode(',', [
                    self::csvField($city), $country, $row['date'], $row['celsius'], $row['fahrenheit'],
                ]);
            }

            return new Response(implode("\n", $lines)."\n", headers: ['Content-Type' => 'text/plain; charset=UTF-8']);
        }

        return $this->json($data);
    }

    private static function csvField(string $field): string
    {
        return strpbrk($field, ',"') === false ? $field : '"'.str_replace('"', '""', $field).'"';
    }
}
