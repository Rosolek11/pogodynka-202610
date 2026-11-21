<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MeasurementTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = (new Measurement())->setCelsius($celsius);

        self::assertSame($expectedFahrenheit, $measurement->getFahrenheit());
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-0.5', '31.1'],
            ['-40', '-40'],
            ['21.5', '70.7'],
            ['36.6', '97.88'],
            ['-17.8', '-0.04'],
            ['12.3', '54.14'],
        ];
    }
}
