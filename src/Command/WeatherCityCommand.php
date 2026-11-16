<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\WeatherUtil;
use App\Entity\Measurement;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('weather:city', 'Show the weather forecast by country and city')]
final class WeatherCityCommand
{
    public function __construct(private WeatherUtil $weather)
    {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $countryCode,
        #[Argument('City name')] string $city,
    ): int {
        $countryCode = strtoupper(trim($countryCode));
        $city = trim($city);
        $measurements = $this->weather->getWeatherForCountryAndCity($countryCode, $city);
        $io->writeln(sprintf('Location: %s (%s)', $city, $countryCode));
        $io->table(['Date', 'Temperature'], array_map(static fn (Measurement $measurement): array => [
            $measurement->getDate()->format('Y-m-d'),
            $measurement->getCelsius(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
