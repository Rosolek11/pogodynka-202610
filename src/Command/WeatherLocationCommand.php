<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use App\Entity\Measurement;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('weather:location', 'Show the weather forecast by location ID')]
final class WeatherLocationCommand
{
    public function __construct(private LocationRepository $locations, private WeatherUtil $weather)
    {
    }

    public function __invoke(SymfonyStyle $io, #[Argument('Location ID')] int $id): int
    {
        $location = $this->locations->find($id);
        if ($location === null) {
            $io->error(sprintf('Unknown location ID: %d', $id));

            return Command::FAILURE;
        }

        $measurements = $this->weather->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s', $location->getCity()));
        $io->table(['Date', 'Temperature'], array_map(static fn (Measurement $measurement): array => [
            $measurement->getDate()->format('Y-m-d'),
            $measurement->getCelsius(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
