<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(
        #[MapEntity(expr: 'repository.findOneByCity(city)')] Location $location,
        WeatherUtil $weatherUtil,
    ): Response {
        $measurements = $weatherUtil->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
