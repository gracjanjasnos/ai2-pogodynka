<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    /**
     * Data provider dla testu `testGetFahrenheit`
     */
    public static function dataGetFahrenheit(): array
    {
        return [
            [0, 32],           // 0°C = 32°F
            [-100, -148],      // -100°C = -148°F
            [100, 212],        // 100°C = 212°F
            [0.5, 32.9],       // 0.5°C = 32.9°F
            [-0.5, 31.1],      // -0.5°C = 31.1°F
            [37, 98.6],        // 37°C = 98.6°F
            [10.5, 50.9],      // 10.5°C = 50.9°F
            [-40, -40],        // -40°C = -40°F
            [20, 68],          // 20°C = 68°F
            [50.3, 122.5],     // 50.3°C = 122.5°F
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit(float $celsius, float $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius); 
        $this->assertEquals(
            $expectedFahrenheit,
            $measurement->getFahrenheit(),
            sprintf('%s°C powinno być %s°F', $celsius, $expectedFahrenheit)
        );
    }
}
