<?php

declare(strict_types=1);

namespace Unit\Domain\Services;

use App\Domain\Entities\Transaction;
use App\Domain\Clients\FirstFraudCheckerClientInterface;
use App\Domain\Clients\SecondFraudCheckerClientInterface;
use App\Domain\Services\FraudChecker;
use PHPUnit\Framework\TestCase;
use DateTime;

class FraudCheckerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }


    /**
     * @dataProvider CheckDataProvider
     */
    public function testCheckFunction(Transaction $transaction, bool $auth): void
    {
        $clientFirst = $this->createMock(FirstFraudCheckerClientInterface::class);
        $clientFirst->method('check')
            ->willReturn($auth);

        $clientSecond = $this->createMock(SecondFraudCheckerClientInterface::class);
        $clientSecond->method('check')
            ->willReturn($auth);

        $service = new FraudChecker($clientFirst, $clientSecond);
        $received = $service->check($transaction);
        $this->assertEquals($auth, $received);
    }

    public function CheckDataProvider(): array
    {
        $entitie = new Transaction;
        return [
            'Não autorizado' => [$entitie, false],
            'Autorizado' => [$entitie, true],
        ];
    }

}
