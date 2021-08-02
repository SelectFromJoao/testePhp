<?php

declare(strict_types=1);

namespace Unit\Domain\Services;

use App\Domain\Entities\Transaction;
use App\Domain\Clients\FirstFraudCheckerClientInterface;
use App\Domain\Clients\SecondFraudCheckerClientInterface;
use App\Domain\Clients\TaxManagerClientInterface;
use App\Domain\Services\TaxCalculator;
use App\Domain\Services\Notifier;
use App\Domain\Clients\NotifierClientInterface;
use App\Domain\Repositories\TransactionRepositoryInterface;
use App\Domain\Services\TransactionDataFill;
use App\Domain\Services\TransactionHandler;
use App\Domain\Services\FraudChecker;
use App\Domain\Entities\Buyer;
use App\Domain\Entities\Notification;
use App\Domain\Entities\Seller;
use PHPUnit\Framework\TestCase;
use App\Domain\Utils\SlytherinDate;
use App\Domain\Utils\SlytherinGuid;
use App\Domain\Exceptions\FraudCheckerException;
use DateTime;

class TransactionHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider HandlerDataProvider
     */
    public function testHandlerFunction(Transaction $transaction, float $SlytherinTax, float $totalTax, float $totalAmount, string $id, string $newData): void
    {
        $clientTax  = $this->createMock(TaxManagerClientInterface::class);
        $serviceTax = new TaxCalculator($clientTax);
        //tax

        $date = $this->createMock(SlytherinDate::class);
        $date->method('currentTime')
            ->willReturn(new Datetime($newData));

       $Guid = $this->getMockBuilder('App\Domain\Uitls\SlytherinGuid')
            ->disableOriginalConstructor()
            ->setMethods(['Guid'])
            ->getMock();

        $Guid->method('Guid')
            ->willReturn($id);

        $serviceFillTransaction = new TransactionDataFill($serviceTax, $date, $Guid);

        //TransactionDataFill

        $clientNotifier = $this->createMock(NotifierClientInterface::class);
        $clientNotifier->method('notify')
        ->willReturn(true);

        $serviceNotifier = new Notifier($clientNotifier);  

        //notifier
        
        $clientFirst = $this->createMock(FirstFraudCheckerClientInterface::class);
        $clientFirst->method('check')
            ->willReturn(true);

        $clientSecond = $this->createMock(SecondFraudCheckerClientInterface::class);
        $clientSecond->method('check')
            ->willReturn(true);

        $serviceFraudChecker = new FraudChecker($clientFirst, $clientSecond);

        //serviceFraudChecker

        $TransactionRepositoryInterface = $this->createMock(TransactionRepositoryInterface::class);

        //TransactionRepositoryInterface

        $TransactionHandler = new TransactionHandler($TransactionRepositoryInterface, $serviceFraudChecker, $serviceNotifier, $serviceFillTransaction);
        $notifyBuyer = new notification;
        $notifySeller = new notification; 
        $TransactionHandler->Create($transaction, $notifyBuyer,  $notifySeller );

        $this->assertEquals( $SlytherinTax ,$transaction->getSlytherinPayTax());

        $Seller = $transaction->getSeller();
        $Buyer  = $transaction->getBuyer();
        $this->assertEquals( $Seller->getEmail(), $notifySeller->GetEmail());

        $this->assertEquals( $Buyer->getEmail(), $notifyBuyer->GetEmail());
    }

    public function HandlerDataProvider(): array
    {
        $Buyer = New Buyer;
        $Buyer->setName('SeveroSnape');
        $Buyer->setEmail('SeveroSnapeBruxao@hogwarts.net');

        $Seller = New Seller;
        $Seller->setName('harry potter');
        $Seller->setEmail('harryPotter@hogwarts.net');

        $entitie = new Transaction;
        $entitie->setInitialAmount(100);
        $entitie->setSellerTax(1);
        $entitie->setCreatedDate(new Datetime('2021-08-01 12:50'));
        $entitie->SetId('6b8be65858ae422bbf22a8fe44bc81ef');

        $entitie->SetSeller($Seller);
        $entitie->SetBuyer($Buyer);

        return [
            'Entidade 1' => [$entitie, 3.140000000000015, 4.140000000000015 , 104.14000000000001, '6b8be65858ae422bbf22a8fe44bc81ef', '2021-08-01 12:50'],
        ];
    }


      /**
     * @dataProvider HandlerExceptionDataProvider
     */
    public function testHandlerExceptionFunction(Transaction $transaction, float $SlytherinTax, float $totalTax, float $totalAmount, string $id, string $newData): void
    {
        $message = 'successful operation';

        $clientTax  = $this->createMock(TaxManagerClientInterface::class);
        $serviceTax = new TaxCalculator($clientTax);
        //tax

        $date = $this->createMock(SlytherinDate::class);
        $date->method('currentTime')
            ->willReturn(new Datetime($newData));

       $Guid = $this->getMockBuilder('App\Domain\Uitls\SlytherinGuid')
            ->disableOriginalConstructor()
            ->setMethods(['Guid'])
            ->getMock();

        $Guid->method('Guid')
            ->willReturn($id);

        $serviceFillTransaction = new TransactionDataFill($serviceTax, $date, $Guid);

        //TransactionDataFill

        $clientNotifier = $this->createMock(NotifierClientInterface::class);
        $clientNotifier->method('notify')
        ->willReturn(true);

        $serviceNotifier = new Notifier($clientNotifier);  

        //notifier
        
        $clientFirst = $this->createMock(FirstFraudCheckerClientInterface::class);
        $clientFirst->method('check')
            ->willReturn(false);

        $clientSecond = $this->createMock(SecondFraudCheckerClientInterface::class);
        $clientSecond->method('check')
            ->willReturn(false);

        $serviceFraudChecker = new FraudChecker($clientFirst, $clientSecond);

        //serviceFraudChecker

        $TransactionRepositoryInterface = $this->createMock(TransactionRepositoryInterface::class);

        //TransactionRepositoryInterface

        $TransactionHandler = new TransactionHandler($TransactionRepositoryInterface, $serviceFraudChecker, $serviceNotifier, $serviceFillTransaction);
        
        $this->expectException(FraudCheckerException::class);

        $notifyBuyer = new notification;
        $notifySeller = new notification; 
              
        $TransactionHandler->Create($transaction, $notifyBuyer, $notifySeller);

    }

    public function HandlerExceptionDataProvider(): array
    {
        $Buyer = New Buyer;
        $Buyer->setName('SeveroSnape');
        $Buyer->setEmail('SeveroSnapeBruxao@hogwarts.net');

        $Seller = New Seller;
        $Seller->setName('harry potter');
        $Seller->setEmail('harryPotter@hogwarts.net');

        $entitie = new Transaction;
        $entitie->setInitialAmount(100);
        $entitie->setSellerTax(1);
        $entitie->setCreatedDate(new Datetime('2021-08-01 12:50'));
        $entitie->SetId('6b8be65858ae422bbf22a8fe44bc81ef');

        $entitie->SetSeller($Seller);
        $entitie->SetBuyer($Buyer);

        return [
            'Entidade 1' => [$entitie, 3.140000000000015, 4.140000000000015 , 104.14000000000001, '6b8be65858ae422bbf22a8fe44bc81ef', '2021-08-01 12:50'],
        ];
    }

}
