<?php

declare(strict_types=1);

namespace Unit\Domain\Services;

use App\Domain\Entities\Transaction;
use App\Domain\Services\TaxCalculator;
use App\Domain\Clients\TaxManagerClientInterface;
use App\Domain\Services\TransactionDataFill;
use PHPUnit\Framework\TestCase;
use App\Domain\Utils\SlytherinDate;
use App\Domain\Utils\SlytherinGuid;
use DateTime;

class TransacionDataFillTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider TransacionDataProvider
     */
    public function testFillDataTransactionFunction(Transaction $entitieExpcted, float $amount, float $tax, float $SlytherinPayTax, float $TotalTax, float $TotalAmount, string $id, string $newData): void
    {
        $client  = $this->createMock(TaxManagerClientInterface::class);
        $service = new TaxCalculator($client);

        $date = $this->createMock(SlytherinDate::class);
        $date->method('currentTime')
             ->willReturn(new Datetime($newData));

       $Guid = $this->getMockBuilder('App\Domain\Uitls\SlytherinGuid')
            ->disableOriginalConstructor()
            ->setMethods(['Guid'])
            ->getMock();

        $Guid->method('Guid')
            ->willReturn($id);
        
        $entitie = new Transaction;
        $serviceFillTransaction = new TransactionDataFill($service, $date, $Guid);

        $entitie->setInitialAmount($amount);
        $entitie->setSellerTax($tax);
        $serviceFillTransaction->FillDataTransaction($entitie);
        $this->assertEquals($amount, $entitie->getInitialAmount());
        $this->assertEquals($entitieExpcted, $entitie);
        $this->assertEquals($tax, $entitie->getSellerTax());
        $this->assertEquals($id, $entitie->getId());
        $this->assertEquals(new Datetime($newData), $entitie->getCreatedDate());
        $this->assertEquals($SlytherinPayTax, $entitie->getSlytherinPayTax());
        $this->assertEquals($TotalTax, $entitie->getTotalTax());
        $this->assertEquals($TotalAmount, $entitie->getTotalAmount());
        
    }

    public function TransacionDataProvider(): array
    {
        $entitie = new Transaction;
        $entitie->setInitialAmount(100);
        $entitie->setSellerTax(1);
        $entitie->setTotalAmount(104.14000000000001);
        $entitie->setTotalTax(4.140000000000015);
        $entitie->setSlytherinPayTax(3.140000000000015);
        $entitie->setCreatedDate(new Datetime('2021-08-01 12:50'));
        $entitie->SetId('6b8be65858ae422bbf22a8fe44bc81ef');

        return [
            'Entidade 1' => [$entitie, 100, 1, 3.140000000000015, 4.140000000000015 , 104.14000000000001, '6b8be65858ae422bbf22a8fe44bc81ef', '2021-08-01 12:50'],
        ];
    }

}
