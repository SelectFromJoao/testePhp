<?php

declare(strict_types=1);

namespace Unit\Domain\Services;

use App\Domain\Clients\TaxManagerClientInterface;
use App\Domain\Services\TaxCalculator;
use PHPUnit\Framework\TestCase;

class TaxCalculatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider taxDataProvider
     */
    public function testCalculateFunction(float $clientIncrementReturn, float $amount, float $tax, float $expected, int $countClientCalls): void
    {
        $client = $this->createMock(TaxManagerClientInterface::class);
        $client->expects($this->exactly($countClientCalls))
            ->method('getIncrementValue')
            ->willReturn($clientIncrementReturn);
        $service = new TaxCalculator($client);
        $received = $service->calculate($amount, $tax);
        $this->assertEquals($expected, $received);
    }

    public function taxDataProvider(): array
    {
        return [
            'menor que o esperado para taxa dinamica' => [0.0, 100, 1, 104.14, 0],
            'igual que o esperado para taxa dinamica' => [0.0, 100, 2, 105.14, 0],
            'igual que o esperado para taxa dinamica com taxa igual a 5' => [0.0, 100, 5, 108.14, 0],
            'maior que o esperado para taxa dinamica' => [16.0, 100, 7, 123, 1],
        ];
    }
}
