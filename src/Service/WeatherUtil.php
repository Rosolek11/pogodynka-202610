<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

final class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locations,
        private readonly MeasurementRepository $measurements,
    ) {
    }

    /** @return Measurement[] */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurements->findByLocation($location);
    }

    /** @return Measurement[] */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locations->findOneBy([
            'countryCode' => strtoupper(trim($countryCode)),
            'city' => trim($city),
        ]);

        return $location === null ? [] : $this->getWeatherForLocation($location);
    }
}
