<?php

namespace App\Command;

use App\Entity\Location;
use App\Entity\Measurement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed-weather', description: 'Dodaje przykladowe miejscowosci i pomiary.')]
class SeedWeatherCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ([
            ['Szczecin', '53.4289000', '14.5530000', ['10.0', '8.0']],
            ['Police', '53.5521000', '14.5718000', ['9.0', '7.0']],
        ] as [$city, $latitude, $longitude, $temperatures]) {
            $location = $this->em->getRepository(Location::class)->findOneBy(['city' => $city]);
            if (!$location) {
                $location = (new Location())->setCity($city)
                    ->setLatitude($latitude)->setLongitude($longitude);
                $this->em->persist($location);
                $this->em->flush();
            }

            foreach (['2026-12-01', '2026-12-02'] as $i => $day) {
                $date = new \DateTime($day);
                $measurement = $this->em->getRepository(Measurement::class)->findOneBy([
                    'location' => $location, 'date' => $date,
                ]);
                if (!$measurement) {
                    $measurement = (new Measurement())->setLocation($location)->setDate($date);
                    $this->em->persist($measurement);
                }
                $measurement->setCelsius($temperatures[$i])->setHumidity(75 + $i)
                    ->setPressure((string) (1015 - $i))->setPrecipitation((string) $i);
            }
            $this->em->flush();
            $output->writeln($city . ': 2 pomiary, id miejscowosci = ' . $location->getId());
        }

        return Command::SUCCESS;
    }
}
