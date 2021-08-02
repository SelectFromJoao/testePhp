<?php

declare(strict_types=1);

namespace Unit\Domain\Services;

use App\Domain\Clients\NotifierClientInterface;
use App\Domain\Entities\Transaction;
use App\Domain\Entities\Notification;
use App\Domain\Services\Notifier;
use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Buyer;
use App\Domain\Entities\Seller;
use App\Domain\Exceptions\NotifierException;
use DateTime;

class NotifierTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider NotifyBuyerDataProvider
     */
    public function testNotifyBuyerFunction(Transaction $transaction , notification  $BuyerExpected, string $id, string $name, string $email, string $message): void
    {
        $buyer = new Buyer;
        $buyer->setId($id);
        $buyer->setName($name);     
        $buyer->setEmail($email);   
        $transaction->setBuyer($buyer);

        $client = $this->createMock(NotifierClientInterface::class);
        $client->method('notify')
        ->willReturn(true);

        $service = new Notifier($client);

        $notificationBuyer = new Notification;
        $notificationBuyer->SetMessage($message);
        $service->notifyBuyer($transaction->getBuyer(), $notificationBuyer);

        $this->assertEquals($id, $buyer->getId());
        $this->assertEquals($email, $buyer->getEmail());
        $this->assertEquals($name, $buyer->getName());
        $this->assertEquals($email, $notificationBuyer->getEmail());
        $this->assertEquals($message, $notificationBuyer->getMessage());
        $this->assertEquals($BuyerExpected, $notificationBuyer);
    }

    public function NotifyBuyerDataProvider(): array
    {
        $entitie = new Transaction;
        $entitie->setInitialAmount(100);
        $entitie->setSellerTax(1);
        $entitie->setTotalTax(104.14000000000001);
        $entitie->setSlytherinPayTax(3.140000000000015);
        $entitie->setCreatedDate(new Datetime('2021-08-01 12:50'));
        $entitie->SetId('6b8be65858ae422bbf22a8fe44bc81ef');

        $notificationBuyerExpected = new notification;
        $notificationBuyerExpected->setEmail('SeveroSnapeBruxao@hogwarts.net');
        $notificationBuyerExpected->setMessage('Operação processada com sucesso');

        return [
            'Notificar Comprador' => [$entitie, $notificationBuyerExpected, '24fa543f5e8b4d60a5ee30013805a5ef', 'Severo Snape', 'SeveroSnapeBruxao@hogwarts.net', 'Operação processada com sucesso'],
        ];
    }

    /**
     * @dataProvider NotifySellerDataProvider
     */
    public function testNotifySellerFunction(Transaction $transaction , notification  $SellerExpected, string $id, string $name, string $email, string $message): void
    {
        $Seller = new Seller;
        $Seller->setId($id);
        $Seller->setName($name);     
        $Seller->setEmail($email);   
        $transaction->setSeller($Seller);

        $client = $this->createMock(NotifierClientInterface::class);
        $client->method('notify')
        ->willReturn(true);


        $service = new Notifier($client);

        $notificationSeller = new Notification;
        $notificationSeller->SetMessage($message);

        $service->notifySeller($transaction->getSeller(), $notificationSeller);

        $this->assertEquals($id, $Seller->getId());
        $this->assertEquals($email, $Seller->getEmail());
        $this->assertEquals($name, $Seller->getName());
        $this->assertEquals($email, $notificationSeller->getEmail());
        $this->assertEquals($message, $notificationSeller->getMessage());
        $this->assertEquals($SellerExpected, $notificationSeller);
    }

    public function NotifySellerDataProvider(): array
    {
        $entitie = new Transaction;
        $entitie->setInitialAmount(100);
        $entitie->setSellerTax(1);
        $entitie->setTotalTax(104.14000000000001);
        $entitie->setSlytherinPayTax(3.140000000000015);
        $entitie->setCreatedDate(new Datetime('2021-08-01 12:50'));
        $entitie->SetId('6b8be65858ae422bbf22a8fe44bc81ef');

        $notificationSellerExpected = new notification;
        $notificationSellerExpected->setEmail('harry@hogwarts.net');
        $notificationSellerExpected->setMessage('Operação processada com sucesso');

        return [
            'Notificar Vendedor' => [$entitie, $notificationSellerExpected, '24fa543f5e8b4d60a5ee30013805a5ef', 'Severo Snape', 'harry@hogwarts.net', 'Operação processada com sucesso'],
        ];
    }

    public function testExceptionSellerNotifyFunction(): void
    {
        $client = $this->createMock(NotifierClientInterface::class);
        $client->method('notify')
        ->willReturn(false);

        $service = new Notifier($client);

        $notificationSeller = new Notification;
        $Seller = new Seller;
        $Seller->setEmail('harry@hogwarts.net');
        $notificationSeller->setMessage('Operação processada com sucesso');

        $this->expectException(NotifierException::class);

        $service->notifySeller($Seller, $notificationSeller);

    }

    public function testExceptionBuyerNotifyFunction(): void
    {
        $client = $this->createMock(NotifierClientInterface::class);
        $client->method('notify')
        ->willReturn(false);

        $service = new Notifier($client);

        $notificationBuyer = new Notification;
        $Buyer = new Buyer;
        $Buyer->setEmail('harry@hogwarts.net');
        $notificationBuyer->setMessage('Operação processada com sucesso');

        $this->expectException(NotifierException::class);

        $service->notifyBuyer($Buyer, $notificationBuyer);

    }

}
